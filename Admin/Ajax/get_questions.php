<?php
include "../Connection/conn.inc.php";
include "../Includes/functions.inc.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Params
$draw   = intval(get_safe_value($conn, $_GET['draw']));
$start  = intval(get_safe_value($conn, $_GET['start']));
$length = intval(get_safe_value($conn, $_GET['length']));
$search = get_safe_value($conn, $_GET['search']['value']);

// Base WHERE
$where  = "WHERE status=1";
if ($search !== '') {
    $where .= " AND questions LIKE '%{$search}%'";
}

// Total & filtered counts
$totalRes    = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM questions WHERE status=1");
$total       = mysqli_fetch_assoc($totalRes)['cnt'];
$filRes      = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM questions $where");
$filtered    = mysqli_fetch_assoc($filRes)['cnt'];

// Fetch paged questions
$sql = "SELECT
          id               AS question_id,
          questions,
          question_image,
          question_type,
          question_id      AS question_unique_id,
          no_of_times_correctly_attempted  AS correct_count,
          no_of_times_attempted            AS attempt_count
        FROM questions
        $where
        ORDER BY id ASC LIMIT $start, $length";
        // echo $sql . "<br /><br /><br /><br /><br /><br /><br /><br /><br />";
$res = mysqli_query($conn, $sql);

$data = [];
while ($q = mysqli_fetch_assoc($res)) {
    // compute percentage & difficulty
    $pct = $q['attempt_count']
         ? round($q['correct_count'] / $q['attempt_count'] * 100, 2) . '%'
         : '0%';
    $diff = 'N/A';
    if ($q['attempt_count'] > 0) {
      if (rtrim($pct, '%') > 75)      $diff = 'Easy';
      elseif (rtrim($pct, '%') < 30)  $diff = 'Difficult';
      else                            $diff = 'Normal';
    }
    $q['percentage']  = $pct;
    $q['difficulty']  = $diff;
    $all_topics = [];
    $topicRes = mysqli_query($conn, "SELECT id, topic FROM topic");
    while ($topic = mysqli_fetch_assoc($topicRes)) {
      $all_topics[] = [$topic['id'], $topic['topic']];
    }
    foreach ($all_topics as $key => $value) {
      if(count($all_topics[$key]) < 3) {
        array_push($all_topics[$key], (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM question_topic_mapping WHERE question_id = {$q['question_id']} AND topic_id = {$all_topics[$key][0]}")) > 0)? 1 : 0);
      }
    }
    $all_main_groups = [];
    $mainGroupRes = mysqli_query($conn, "SELECT id, main_group FROM main_group");
    while ($main_group = mysqli_fetch_assoc($mainGroupRes)) {
      $all_main_groups[] = [$main_group['id'], $main_group['main_group']];
    }
    foreach ($all_main_groups as $key => $value) {
      if(count($all_main_groups[$key]) < 3) {
        array_push($all_main_groups[$key], (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM question_main_group_mapping WHERE question_id = {$q['question_id']} AND main_group_id = {$all_main_groups[$key][0]}")) > 0)? 1 : 0);
      }
    }
    $all_sub_groups = [];
    $subGroupRes = mysqli_query($conn, "SELECT id, sub_group FROM sub_group");
    while ($sub_group = mysqli_fetch_assoc($subGroupRes)) {
      $all_sub_groups[] = [$sub_group['id'], $sub_group['sub_group']];
    }
    foreach ($all_sub_groups as $key => $value) {
      if(count($all_sub_groups[$key]) < 3) {
        array_push($all_sub_groups[$key], (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM question_sub_group_mapping WHERE question_id = {$q['question_id']} AND sub_group_id = {$all_sub_groups[$key][0]}")) > 0)? 1 : 0);
      }
    }
    $q['all_topics']  = $all_topics;
    $q['all_main_groups']  = $all_main_groups;
    $q['all_sub_groups']  = $all_sub_groups;

    // fetch options if not a title question
    $q['options'] = [];
    if ($q['question_type'] !== 'title') {
        $optSql = "SELECT answers, is_correct
                   FROM options
                   WHERE question_id = {$q['question_id']}
                     AND status = 1";
        $optRes = mysqli_query($conn, $optSql);
        while ($opt = mysqli_fetch_assoc($optRes)) {
            $q['options'][] = [
              'text'      => $opt['answers'],
              'is_correct'=> (bool)$opt['is_correct']
            ];
        }
    }

    $data[] = $q;
}

// JSON response
echo json_encode([
  'draw'            => $draw,
  'recordsTotal'    => (int)$total,
  'recordsFiltered' => (int)$filtered,
  'data'            => $data
]);
