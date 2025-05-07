<?php
include "../Connection/conn.inc.php";
include "../Includes/functions.inc.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Params
$draw   = intval(get_safe_value($conn, $_POST['draw']));
$start  = intval(get_safe_value($conn, $_POST['start']));
$length = intval(get_safe_value($conn, $_POST['length']));
$search = get_safe_value($conn, $_POST['search']['value']);

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
          question_type,
          no_of_times_correctly_attempted  AS correct_count,
          no_of_times_attempted            AS attempt_count
        FROM questions
        $where
        ORDER BY id ASC";
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
