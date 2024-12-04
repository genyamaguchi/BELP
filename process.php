<?php
date_default_timezone_set("America/Los_Angeles");
// File where ratings are stored
$file = 'ratings_compact.csv';

// Get today's date
// If todays date is December 4th, 2024, $today will be formatted as 12/04/2024
$today = date('m/d/Y');

// Load food names and ratings from ratings_compact.csv
$foodData = [];
if (file_exists($file)) {
    $fp = fopen($file, 'r');
    $header = fgetcsv($fp, 1000, ',', '"', '\\');
    while (($row = fgetcsv($fp, 1000, ',', '"', '\\')) !== false) {
        $foodData[$row[0]] = [
            'food' => $row[1],
            'ratings' => array_map('intval', array_slice($row, 2))
        ];
    }
    fclose($fp);
}

// The food name if not found becomes "Unknown Food". If prexisting, it will be whatever is in column 2 of csv.
$foodName = isset($foodData[$today]) ? $foodData[$today]['food'] : 'Unknown Food';

// Uses these default rating counts if not found
if (!isset($foodData[$today])) {
    $foodData[$today] = [
        'food' => $foodName,
        'ratings' => [0, 0, 0, 0, 0]
    ];
}

// Get the user's rating
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

// Prevents user from submitting empty rating
// die statement is hidden though
if ($rating < 1 || $rating > 5) {
    die("Invalid rating. Please provide a rating between 1 and 5.");
}

// Update the rating count
$foodData[$today]['ratings'][$rating - 1] += 1;

// Write updated data back to ratings_compact.csv
$fp = fopen($file, 'w');
fputcsv($fp, ['date', 'meal', 'ratings_1', 'ratings_2', 'ratings_3', 'ratings_4', 'ratings_5'],escape: ""); // https://php.watch/versions/8.4/csv-functions-escape-parameter BIGGEST LIFESAVER, SAVED MY CODE
foreach ($foodData as $date => $data) {
    fputcsv($fp, array_merge([$date, $data['food']], $data['ratings']), ',', '"', escape: "");
}
fclose($fp);

// Calculate the average rating for today
$totalRatings = array_sum($foodData[$today]['ratings']);
$totalVotes = 0;
for ($i = 0; $i < 5; $i++) {
    $totalVotes += $foodData[$today]['ratings'][$i] * ($i + 1);
    // $i goes from 0-4 representing the 5 star levels. 
    // $foodData[$today]['ratings'][$i] gives number of ratings for each particular star (eg 3 ratigns for 1 star)
    // ($i + 1) represents star rating 1,2,3, etc
}
$averageRating = $totalRatings > 0 ? round($totalVotes / $totalRatings, 2) : 0;
// rounds to 2 decimal places

// Return a JSON response with the average rating and success message
echo json_encode([
    'averageRating' => $averageRating
]);
// This is run by AJAX which is handled by the client side JS. Its not something that will appear on the webpage.

?>
