<?php
include("PHP/iheader.php");
?>
    <!DOCTYPE html>
    <html>

    <body>
        <main class="Posts">
            <div class="Sidebar">
                <div class="search">
                    <h3>Search</h3>
                    <?php
                        include('PHP/search.php');
                    ?>
                </div>
                <div class="tags">
                    <h2>Popular Tags</h2>
                    <table>
                        <?php
                        $tquery = $DBH->prepare('SELECT tag, COUNT(*) AS Tagcount FROM BO_tags, BO_image_tags WHERE BO_tags.TID = BO_image_tags.tag_id GROUP BY tag ORDER BY Tagcount DESC LIMIT 20;');
                        //$tquery = $DBH->prepare('SELECT tag, tcount FROM BO_tags ORDER BY tcount DESC LIMIT 20;');
                        $tquery->execute();
                        $tquery->setFetchMode(PDO::FETCH_OBJ);
                        $tagcount = $tquery->rowCount();
                        //SELECT tag, COUNT(*) AS Tagcount FROM BO_tags, BO_image_tags WHERE BO_tags.TID = BO_image_tags.tag_id GROUP BY tag ORDER BY Tagcount DESC LIMIT 20;
                        for($i=0;$i<$tagcount;$i++){
                        $tags = $tquery->fetch();
                        echo('<tr>
                        <td><a href="posts.php?tsearch='. ($tags->tag) .'">'. ($tags->tag) . '</a></td>
                        <td>(' . ($tags->Tagcount) . ')</td>
                        </tr>');
                        }
                    ?>
                    </table>
                </div>
            </div>
            <div class="pics">
                <?php
            if($_GET['tsearch'] !== null){
                if($_GET['tsearch'] === ''){
                    unset($_GET['tsearch']);
                    redirect('posts.php');
                }else{
                    $stags = preg_split("/, /", $_GET['tsearch']);
                    $tamount = count($stags);
                    if($_GET['page'] === null or $_GET['page'] === 0){
                    if ($tamount == 1) {
                            $PIC = $DBH->prepare('SELECT IID, filename FROM BO_images, BO_image_tags, BO_tags WHERE BO_tags.tag ="' . $stags[0] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = BO_images.IID ORDER BY posted DESC LIMIT 50;');
                            $PIC->execute();
                            $PICCOUNT = $PIC->rowCount();
                    }elseif($tamount == 2){
                            $PIC = $DBH->prepare('SELECT IID, filename FROM (
                            SELECT IID, filename FROM BO_images, BO_image_tags, BO_tags WHERE  BO_tags.tag = "' . $stags[0] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = BO_images.IID ORDER BY posted DESC) AS TF, BO_tags, BO_image_tags
                            WHERE BO_tags.tag = "' . $stags[1] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = TF.IID LIMIT 50;');
                            $PIC->execute();
                            $PICCOUNT = $PIC->rowCount();
                    }elseif($tamount == 3){
                            $PIC = $DBH->prepare('SELECT IID, filename FROM (
	SELECT IID, filename FROM (
    	SELECT IID, filename FROM BO_images, BO_image_tags, BO_tags WHERE  BO_tags.tag = "' . $stags[0] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = BO_images.IID ORDER BY posted DESC) AS TF, BO_tags, BO_image_tags
    WHERE BO_tags.tag = "' . $stags[1] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = TF.IID) AS TF2, BO_tags, BO_image_tags
WHERE BO_tags.tag = "' . $stags[2] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = TF2.IID LIMIT 50;');
                            $PIC->execute();
                            $PICCOUNT = $PIC->rowCount();
                    }else{
                        $_SESSION['message'] = 'Maximum of three tags please.';
                        unset($_GET['tsearch']);
                        redirect('posts.php');
                    };
                }else{
                        if ($tamount == 1) {
                            $PIC = $DBH->prepare('SELECT IID, filename FROM BO_images, BO_image_tags, BO_tags WHERE BO_tags.tag ="' . $stags[0] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = BO_images.IID ORDER BY posted DESC LIMIT ' . (($_GET['page']-1) * 50) . ',50;');
                            $PIC->execute();
                            $PICCOUNT = $PIC->rowCount();
                    }elseif($tamount == 2){
                            $PIC = $DBH->prepare('SELECT IID, filename FROM (
                            SELECT IID, filename FROM BO_images, BO_image_tags, BO_tags WHERE  BO_tags.tag = "' . $stags[0] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = BO_images.IID ORDER BY posted DESC) AS TF, BO_tags, BO_image_tags
                            WHERE BO_tags.tag = "' . $stags[1] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = TF.IID LIMIT ' . (($_GET['page']-1) * 50) . ',50;');
                            $PIC->execute();
                            $PICCOUNT = $PIC->rowCount();
                    }elseif($tamount == 3){
                            $PIC = $DBH->prepare('SELECT IID, filename FROM (
	SELECT IID, filename FROM (
    	SELECT IID, filename FROM BO_images, BO_image_tags, BO_tags WHERE  BO_tags.tag = "' . $stags[0] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = BO_images.IID ORDER BY posted DESC) AS TF, BO_tags, BO_image_tags
    WHERE BO_tags.tag = "' . $stags[1] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = TF.IID) AS TF2, BO_tags, BO_image_tags
WHERE BO_tags.tag = "' . $stags[2] . '" AND BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = TF2.IID LIMIT ' . (($_GET['page']-1) * 50) . ',50;');
                            $PIC->execute();
                            $PICCOUNT = $PIC->rowCount();
                    }else{
                        $_SESSION['message'] = 'Maximum of three tags please.';
                        unset($_GET['tsearch']);
                        redirect('posts.php');
                    };
                    };
                };
            }else{
                if($_GET['page'] === null or $_GET['page'] === 0){
                    $PIC = $DBH->prepare("SELECT IID, filename FROM BO_images ORDER BY posted DESC LIMIT 50");
                    $PIC->execute();
                    $PICCOUNT = $PIC->rowCount();
                }else{
                    $PIC = $DBH->prepare('SELECT IID, filename FROM BO_images ORDER BY posted DESC LIMIT ' . (($_GET['page']-1) * 50) . ',50;');
                    $PIC->execute();
                    $PICCOUNT = $PIC->rowCount();
                };
            };
            for($i=0;$i<$PICCOUNT;$i++){
                $image = $PIC->fetch();
                
                echo('<a href="show.php?post=' . $image['filename'] . '"><img src="uploads/' . $image['filename'] . '"></a>');
            };
            ?>

                    <!--
            <img src="images/Frupfrup.png">
            <img src="images/Hartaran.png">
            <img src="images/Hyperpuffin.png">
            <img src="images/Spacedra.png">
            <img src="images/Toroka.png">
            <img src="images/Watcher.png">
            -->
            </div>
        </main>
        
<!-- button copy start -->
<div id="testingshit" lass="pagenav">
                    <form method="post">
                        <?php
                            if(2<=$_GET['page']){
                                echo ('<input id=prev type="submit" name="PREV" value="Previous"/>');};
                               
                         ?>  
                         </form>
                        <?php
                          if($_GET['page'] === null){
                                    echo ('<p class="pnumb">1</p>');
                                }else{
                                    echo ('<p class="pnumb">' . $_GET['page'] . '</p>');
                                };
                        ?>
                    <form method="post">
                        <?php
                            if($PICCOUNT >= 50){
                                echo '<input id="next" type="submit" name="NEXT" value="Next"/>';
                            };
                        ?>
                    </form>
                    <?php
                        if($_POST['NEXT']){
                            if($_GET['tsearch'] === null){
                                if($_GET['page'] === null){
                                    $_GET['page'] = 2;
                                    redirect('posts.php?page=' . $_GET['page']);
                                }else{
                                    $_GET['page'] = $_GET['page'] + 1;
                                    redirect('posts.php?page=' . $_GET['page']);
                                };
                            }else{
                                if($_GET['page'] === null){
                                    $_GET['page'] = 2;
                                    redirect('posts.php?tsearch=' . $_GET['tsearch'] . '&page=' . $_GET['page']);
                                }else{
                                    $_GET['page'] = $_GET['page'] + 1;
                                    redirect('posts.php?tsearch=' . $_GET['tsearch'] . '&page=' . $_GET['page']);
                                };
                            };
                        };
                        if($_POST['PREV']){
                            if($_GET['tsearch'] === null){
                                $_GET['page'] = $_GET['page'] - 1;
                                redirect('posts.php?page=' . $_GET['page']);
                            }else{
                                $_GET['page'] = $_GET['page'] - 1;
                                echo $_GET['page'];
                                redirect('posts.php?tsearch=' . $_GET['tsearch'] . '&page=' . $_GET['page']);
                            };
                        };
                    ?>
                </div>
        <!--button copy end-->

    </body>

    </html>
