<?php 
/**
 * Template for pagination. Uses the info variable to determine
 * how the pagination will be created.
 *
 * Note: $url variable needs to be in the page that is requiring this
 * template.
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
function pageBox($pageNum, $info, $url)
{
    $link = $url;

    if (strpos($link, "?") !== false) {
        $link = $url . "&page=$pageNum";
    } else {
        $link = $url . "?page=$pageNum";
    }

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


$pageNext = $info->page + 1;
$pagePrev = $info->page - 1;
$linkNext = SITE_DIR . "/?page=$pageNext";
$linkPrev = SITE_DIR . "/?page=$pagePrev";

?>

<div class="pagination">
        <div class="pagination-count">
        <h4 class="thin no-margin">Showing
            <?php 
            if ($info->page * $info->limit <= 0) {
                echo "There are no photos to show.";
                return;
            } else {
                echo ($info->page - 1) * $info->limit;
                echo " - ";
                if ($info->page * $info->limit > $info->total) {
                    echo $info->total;
                } else {
                    echo $info->page * $info->limit;
                }
                echo " out of " . $info->total . " photos.";
            }
            ?>
        </h4>
    </div>
    <div class="wrapper">
        
        <?php
        if ($maxPages == 1) {
            return;
        }

        // Previous button doesn't need to be shown if on page 1.
        if ($info->page > 1) {
            echo '<a href="'.$linkPrev.'">
                    <div class="page-box"><p>Previous</p></div>
                </a>';
        }
        ?>
        
        <?php
        // First page should always be shown
        echo pageBox(1, $info, $url);

        // At minimum the current page should be 2 because
        // the first page box was already printed.
        $currPage = $info->page + ($info->page == 1);

        // Displays dots only when current page is beyond page 3
        // Also check if the current page is far from the first page
        // so that it doesn't display dots between 1 and 2.
        if ($currPage - 2 > 2) {
            echo pageDots();
        }

        if ($currPage + 2 >= $maxPages) {
            $currPage = $maxPages - 4;
        } else {
            $currPage -= 2;
        }

        // Minimum has to be 2 since 1 is already printed.
        if ($currPage <= 1) {
            $currPage = 2;
        }

        $range = 5;
        $idx = 0;
        $newCurrPage = $currPage;
        while ($idx < $range && $currPage + $idx < $maxPages) {
            echo pageBox($currPage + $idx++, $info, $url);
            $newCurrPage++;
        }
        $currPage = $newCurrPage;
        if ($currPage <= $maxPages - 1) {
            echo pageDots();
        }

        echo pageBox($maxPages, $info, $url);
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
