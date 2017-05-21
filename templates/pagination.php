<?php 
/**
 * Template for pagination. Uses the info variable to determine
 * how the pagination will be created.
 *
 * Fields
 *      page
 *      limit
 *      count
 *      rows
 *
 * PHP version 5.5.38
 *
 * @category  Template
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */
?>

<div class="pagination">
    <!-- Show no pagination if only 1 page -->
    <!-- << [1] 2 >> -->
    <!-- << [1] 2 3 >> -->
    <!-- << [1] 2 3 ... 100 >> -->
    <!-- << 1 2 [3] ... 100 >> -->
    <!-- << 1 ... [4] 5 6 ... 100 >> -->
    <!-- << 1 ... [5] 6 7 ... 100 >> -->
    <div class="wrapper">
        <div class="page-box"><p>1</p></div>
        <div class="page-dots"><p>...</p></div>
        <div class="page-box"><p>4</p></div>
        <div class="page-box"><p>5</p></div>
        <div class="page-box"><p>6</p></div>
        <div class="page-dots"><p>...</p></div>
        <div class="page-box"><p>100</p></div>
    </div>
</div>
