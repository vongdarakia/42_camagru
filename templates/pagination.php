<?php 
/**
 * Template for pagination. Uses the info variable to determine
 * how the pagination will be created.
 *
 * Fields
 *      page
 *      limit
 *      total
 *      rows
 *
 * Resources:
 *      Best Pagination Practices
 *      https://uxplanet.org/pagination-best-practices-76fbd3f5a78d
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

/**
 * Returns a template for a box with a page number in it.
 * Used for pagination.
 *
 * @param Int    $pageNum The page number we're making the box for.
 * @param Object $info    The object described above. Has info on pagination.
 *
 * @return Boolean if valid.
 */
function pageBox($pageNum, $info)
{
    $link = SITE_DIR . "/?page=$pageNum";
    $class = "page-box";
    if ($info->page == $pageNum) {
        $class = $class ." current-page";
    }
    return '<a href="'.$link.'">
            <div class="'.$class.'">
                <p>'.$pageNum.'</p>
            </div>
        </a>';
}

/**
 * Returns a template for a box with 3 dots.
 * Used for indicating that there are more pages between
 * the two pages.
 *
 * @return Boolean if valid.
 */
function pageDots()
{
    return '<div class="page-dots"><p>...</p></div>';
}

if ($info->page == $maxPages) {
    return;
}

$pageNext = $info->page + 1;
$pagePrev = $info->page - 1;
$linkNext = SITE_DIR . "/?page=$pageNext";
$linkPrev = SITE_DIR . "/?page=$pagePrev";

?>

<div class="pagination">
    <div class="wrapper">
        <?php
        // Previous button doesn't need to be shown if on page 1.
        if ($info->page > 1) {
            echo '<a href="'.$linkPrev.'">
                    <div class="page-box"><p>Previous</p></div>
                </a>';
        }
        ?>
        
        <?php
        // First page should always be shown
        echo pageBox(1, $info);

        // At minimum the current page should be 2 because
        // the first page box was already printed.
        $currPage = $info->page + ($info->page == 1);

        // Displays dots only when current page is beyond page 3
        if ($info->page > 3) {
            echo pageDots();
        } else {
            $currPage = 2;
        }

        // If current page is within a range of 4 under then max page
        // show all pages from max page - 4 to the current page.
        $range = $maxPages - 4;
        if ($currPage > $range) {
            $idx = 0;
            while ($range + $idx < $currPage) {
                if ($range + $idx > 1) {
                    echo pageBox($range + $idx, $info);
                }
                $idx++;
            }
        }

        // Will display up to 3 page boxes minimum
        // or till the max page.
        $idx = 0;
        while ($idx++ < 3 && $currPage < $maxPages) {
            echo pageBox($currPage++, $info);
        }

        // If the current page is within a range of 2 from the max,
        // show those page boxes in between, otherwise show dots to
        // indicate there are many more pages in between.
        if ($maxPages - $currPage < 2) {
            while ($currPage < $maxPages) {
                echo pageBox($currPage++, $info);
            }
        } else {
            echo pageDots();
        }
        echo pageBox($maxPages, $info);
        ?>
        <?php
        // Next button doesn't need to be shown if on last page.
        if ($info->page != $maxPages) {
            echo '<a href="'.$linkNext.'">
                    <div class="page-box"><p>Next</p></div>
                </a>';
        }
        ?>
    </div>
</div>
