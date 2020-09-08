<?php
include("config.php");
include("classes/SiteResultsProvider.php");

if (isset($_GET["term"])) {
    $term = $_GET["term"];
} else {
    exit("you must entr a search term");
}
$type = isset($_GET["type"]) ? $_GET["type"] : "Sites";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Welcome to Noodle</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <div class="headerContent">
                <div class="logoContainer">
                    <a href="index.php">
                        <img src="assets/images/festisite_google.png">
                    </a>
                </div>
                <div class="searchContainer">
                    <form action="search.php" method="GET">
                        <div class="searchBarContainer">
                            <input class="searchBox" type="text" name="term" value="<?php echo $term ?>">
                            <button class="searchButton"><img src="assets/images/icons/search.png"></button>
                        </div>
                    </form>
                </div>

            </div>
            <div class="tabsContainer">
                <ul class="tabList">
                    <li class="<? echo $type == 'sites' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=sites"; ?>'>Sites</a>
                    </li>
                    <li class="<? echo $type == 'images' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=images"; ?>'>Images</a>
                    </li>
                </ul>

            </div>
        </div>




        <div class="mainResultsSection">
            <?php
            $resultsProvider = new SiteResultsProviders($con);
            $pageSize = 20;

            $numResults = $resultsProvider->getNumResults($term);

            echo "<p class='resultsCount'>$numResults results found</p>";

            echo $resultsProvider->getResultshtml($page, $pageSize, $term);
            ?>

        </div>
        <div class="paginationContainer">
            <div class="pageButtons">
                <div class="pageNumberContainer">
                    <img src="assets/images/pageStart.png">
                </div>
                <?php
                $pagesToShow = 10; /* oの表示数のmax */
                $numPages = ceil($numResults / $pageSize); /*小数点切り上げ 総ページ数*/
                $pagesLeft = min($pagesToShow, $numPages); /*min関数は最小値の方を返す ループで回すoの数*/

                // ループ開始地点の設定
                $currentPage = $page - floor($pagesToShow / 2); /*小数点切り下げ 　ループ開始地点*/
                if ($currentPage < 1) {  /*ページ数が5より少ないときのループ開始地点は1から */
                    $currentPage = 1;
                }
                if ($currentPage + $pagesLeft > $numPages + 1) {  /* ページmax付近でのループ開始地点 */
                    $currentPage = $numPages + 1 - $pagesLeft;
                }

                while ($pagesLeft != 0 && $currentPage <= $numPages) { /*currnetPageを増やし、pagesLeftをへらす */
                    if ($currentPage == $page) {
                        echo "<div class='pageNumberContainer'>
                                <img src='assets/images/pageSelected.png'>
                                <span class='pageNumber'>$currentPage</span>
    
                            </div>";
                    } else {
                        echo "<div class='pageNumberContainer'>
                                <a href='search.php?term=$term&type=$type&page=$currentPage'>
                                    <img src='assets/images/page.png'>
                                    <span class='pageNumber'>$currentPage</span>
                                </a>
                            </div>";
                    }
                    $currentPage++;
                    $pagesLeft--;
                }


                ?>




                <div class="pageNumberContainer">
                    <img src="assets/images/pageEnd.png">
                </div>


            </div>


        </div>

    </div>
</body>

</html>