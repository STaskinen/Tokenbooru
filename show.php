<?php
include("PHP/iheader.php");
?>
<!DOCTYPE html>
    <html>

    <body>
        <?php
        $DELETED = 'false';
        ?>
        <main class="Posts">
            <?php
            if($_GET['post'] === '' or $_GET['post'] === null){
                redirect('posts.php');
            };
            ?>
            <div class="Sidebar">
                <div class="search">
                    <h2>Search</h2>
                    <p></p>
                    <form>
                        <input type="text" name="tsearch" placeholder="Search" />
                    </form>
                    <?php
                        if(isset($_GET['tsearch'])){
                                if($_GET['tsearch'] === ''){
                                unset($_GET['tsearch']);
                                redirect('posts.php');
                            }else{
                                redirect('posts.php?tsearch=' . $_GET['tsearch']);
                                };
                        };
                    ?>
                    <p></p>
                </div>
                <div class="tags">
                    <h2>Tags</h2>
                    <table>
                    <?php
                        $IMGSHOW = $_GET['post'];
                        $IDquery = $DBH->prepare('SELECT IID, owner_id AS OID FROM BO_images WHERE filename ="' . $_GET['post'] . '";');
                        $IDquery->execute();
                        $SHOWID = $IDquery->fetch();
                        $tquery = $DBH->prepare('SELECT tag FROM BO_tags, BO_image_tags, BO_images WHERE BO_tags.TID = BO_image_tags.tag_id AND BO_image_tags.image_id = ' . $SHOWID['IID'] . ' GROUP BY tag ORDER BY tag LIMIT 20;');
                        //$tquery = $DBH->prepare('SELECT tag, tcount FROM BO_tags ORDER BY tcount DESC LIMIT 20;');
                        $tquery->execute();
                        $tquery->setFetchMode(PDO::FETCH_OBJ);
                        $tagcount = $tquery->rowCount();
                        //SELECT tag, COUNT(*) AS Tagcount FROM BO_tags, BO_image_tags WHERE BO_tags.TID = BO_image_tags.tag_id GROUP BY tag ORDER BY Tagcount DESC LIMIT 20;
                        for($i=0;$i<$tagcount;$i++){
                        $tags = $tquery->fetch();
                        echo('<tr>
                        <td><a href="posts.php?tsearch='. ($tags->tag) .'">'. ($tags->tag) . '</a></td>
                        </tr>');
                        }
                    ?>
                    </table>
                    <?php
                    if($_SESSION['logged'] === 'true'){
                        echo ('
                            <div class="tagging">
                            <h3>Tagging</h3>
                            <form method="post">
                            <input type="text" name="thetag" pattern="(^[a-zA-Z0-9_]{2,})+( ([a-zA-Z0-9_]{2,})+)*$"
                            oninvalid="setCustomValidity(\'Tags need to be at least three characters long.\')"
                            onchange="try{setCustomValidity(\'\')}catch(e){}"/>
                            <input type="submit" name="addtag" value="Add" />
                            <input type="submit" name="removetag" value="Remove" />
                            </form>
                            </div>
                        ');
                        if(isset($_POST['addtag'])){
                            if($_POST['thetag'] === ''){
                                echo ("You didn't put anything in silly.");
                            }else{
                            $TAGquery = $DBH->prepare('SELECT tag FROM BO_tags WHERE BO_tags.tag = "' . $_POST['thetag'] . '";');
                            $TAGquery->execute();
                            if(0 < $TAGquery->rowCount()){
                                $TIDquery = $DBH->prepare('SELECT TID FROM BO_tags WHERE BO_tags.tag = "' . $_POST['thetag'] . '";');
                                $TIDquery->execute();
                                $TIDfound = $TIDquery->fetch();
                                $linkcheck = $DBH->prepare('SELECT * FROM BO_image_tags WHERE image_id = ' . $SHOWID['IID'] . ' AND tag_id = ' . $TIDfound['TID'] . ';');
                                $linkcheck->execute();
                                if(0<$linkcheck->rowCount()){
                                    echo ('<p>Silly you. This pic is already tagged with the ' . $_POST['thetag'] . ' tag.</p>');
                                }else{
                                $TAGlink = $DBH->prepare('INSERT INTO BO_image_tags VALUES (' . $SHOWID['IID'] . ', ' . $TIDfound['TID'] . ');');
                                $TAGlink->execute();}
                            }else{
                                $TAGins = $DBH->prepare('INSERT INTO BO_tags (tag) VALUES ("' . $_POST['thetag'] . '");');
                                $TAGins->execute();
                                $TIDquery = $DBH->prepare('SELECT TID FROM BO_tags WHERE BO_tags.tag = "' . $_POST['thetag'] . '";');
                                $TIDquery->execute();
                                $TIDfound = $TIDquery->fetch();
                                $TAGlink = $DBH->prepare('INSERT INTO BO_image_tags VALUES (' . $SHOWID['IID'] . ', ' . $TIDfound['TID'] . ');');
                                $TAGlink->execute();
                            };
                            };
                        };
                        if(isset($_POST['removetag'])){
                            $TIDquery = $DBH->prepare('SELECT TID FROM BO_tags WHERE BO_tags.tag = "' . $_POST['thetag'] . '";');
                                $TIDquery->execute();
                                $TIDfound = $TIDquery->fetch();
                            $TAGquery = $DBH->prepare('SELECT tag_id FROM BO_image_tags WHERE BO_image_tags.tag_id = ' . $TIDfound['TID'] . ';');
                            $TAGquery->execute();
                                if(0 === $TAGquery->rowCount()){
                                    echo ("Silly you! That tag doesn't exists.");
                                }else{
                                    if(1 === $TAGquery->rowCount()){
                                        $TAGunlink = $DBH->prepare('DELETE FROM BO_image_tags WHERE BO_image_tags.image_id = ' . $SHOWID['IID'] . ' AND BO_image_tags.tag_id  = ' . $TIDfound['TID'] . ';');
                                        $TAGunlink->execute();
                                        $TAGRMV = $DBH->prepare('DELETE FROM BO_tags WHERE BO_tags.TID = ' . $TIDfound['TID'] . ';');
                                        $TAGRMV->execute();
                                    }else{
                                        $TAGunlink = $DBH->prepare('DELETE FROM BO_image_tags WHERE BO_image_tags.image_id = ' . $SHOWID['IID'] . ' AND BO_image_tags.tag_id  = ' . $TIDfound['TID'] . '');
                                        $TAGunlink->execute();
                                    };
                                };
                        };
                    };
                    ?>
                </div>
                <div class="deleting">
                    <?php
                    if($_SESSION['ID'] === $SHOWID['OID'] or $_SESSION['UT'] === "master" or $_SESSION['UT'] === "mod"){
                        echo '<h4>DELETE THIS PICTURE<h4>';
                        echo '
                            <form method="post">
                                <input type="submit" name="DELETE" Value="DELETE"/>
                            </form>';
                    };
                    if($_POST['DELETE']){
                        $Linkquery = $DBH->prepare('SELECT tag_id, tag FROM BO_image_tags, BO_tags WHERE BO_image_tags.image_id = ' . $SHOWID['IID'] . ' AND BO_image_tags.tag_id = BO_tags.TID;');
                        $Linkquery->execute();
                        for($i=0;$i<$Linkquery->rowCount();$i++){
                            $LQR = $Linkquery->fetch();
                            $TIDquery = $DBH->prepare('SELECT TID FROM BO_tags WHERE BO_tags.tag = "' . $LQR['tag_id'] . '";');
                                $TIDquery->execute();
                                $TIDfound = $TIDquery->fetch();
                            $TAGquery = $DBH->prepare('SELECT tag_id FROM BO_image_tags WHERE BO_image_tags.tag_id = ' . $LQR['tag_id'] . ';');
                            $TAGquery->execute();
                                if(0 === $TAGquery->rowCount()){
                                    echo ("Silly you! That tag doesn't exists.");
                                }else{
                                    if(1 === $TAGquery->rowCount()){
                                        $TAGunlink = $DBH->prepare('DELETE FROM BO_image_tags WHERE BO_image_tags.image_id = ' . $SHOWID['IID'] . ' AND BO_image_tags.tag_id  = ' . $LQR['tag_id'] . ';');
                                        $TAGunlink->execute();
                                        $TAGRMV = $DBH->prepare('DELETE FROM BO_tags WHERE BO_tags.TID = ' . $LQR['tag_id'] . ';');
                                        $TAGRMV->execute();
                                    }else{
                                        $TAGunlink = $DBH->prepare('DELETE FROM BO_image_tags WHERE BO_image_tags.image_id = ' . $SHOWID['IID'] . ' AND BO_image_tags.tag_id  = ' . $LQR['tag_id'] . ';');
                                        $TAGunlink->execute();
                                    };
                                };
                        }
                        $IMGDEL = $DBH->prepare('DELETE FROM BO_images WHERE IID = ' . $SHOWID['IID'] . ';');
                        $IMGDEL->execute();
                        if(unlink('uploads/'. $_GET['post'])){
                            $_SESSION['message'] = 'Token deleted';
                        };
                        redirect('posts.php');
                    };
                    ?>
                </div>
            </div>
            
            <div class="Show">
                <?php
                $iquery = $DBH->prepare('SELECT IID, posted FROM BO_images WHERE filename ="' . $_GET['post'] . '";');
                $iquery->execute();
                $showImg = $iquery->fetch();
                echo ('
                <img src="uploads/'. $_GET['post'] .'">
                <p>Posted: ' . $showImg['posted'] . '</p>
                ');
                ?>
                <div class="comments">
                    <?php
                    if($_SESSION['logged'] === 'true'){
                        echo('
                                <legend>Give a comment</legend>
                                <form method="post">
                                    <textarea class="commenter" name="comment"></textarea>
                                    <input type="submit" name="comsub" value="Post"/>
                                    <input type="reset" value="Reset"/>
                                </from>
                                ');
                        if(isset($_POST['comsub'])){
                            if($_POST['comment'] == ''){
                                echo ('No empty comments please.');
                            }else{
                                $CPUT = $DBH->prepare('INSERT INTO BO_comments (IID, Ctext) VALUES (' . $SHOWID['IID'] . ', "' . $_POST['comment'] . '");');
                                $CPUT->execute();
                                $cmrquery = $DBH->prepare('SELECT CID FROM BO_comments WHERE Ctext = "' . $_POST['comment'] . '" AND BO_comments.IID = ' . $SHOWID['IID'] . ' ;');
                                $cmrquery->execute();
                                $cmr = $cmrquery->fetch();
                                $clink = $DBH->prepare('INSERT INTO BO_commenter values (' . $cmr['CID'] . ', ' . $_SESSION['ID'] . ');');
                                $clink->execute();
                            };
                        };
                    };
                    $cquery = $DBH->prepare('SELECT Ctext, Ptime, BO_comments.CID, uname, BO_commenter.UID FROM BO_comments, BO_users, BO_commenter WHERE BO_comments.IID = ' . $SHOWID['IID'] . ' AND BO_comments.CID = BO_commenter.CID AND BO_commenter.UID = BO_users.UID ORDER BY Ptime;');
                    $cquery->execute();
                    $ccount = $cquery->rowCount();
                    for($a=0;$a<($cquery->rowCount());$a++){
                    $COM = $cquery->fetch();
                        echo('<p>
                            <tr>
                                <td>
                                ID:' . $COM['CID'] . '</br>
                                    '. ($COM['Ctext']) .'
                                </td>
                                <td>
                                    <h4>
                                        Posted</br>'. ($COM['Ptime']) . '
                                    </h4>
                                    <p>
                                    Poster: ' . $COM['uname'] . '
                                    </p>
                                </td>
                            </tr>
                            </p>
                        ');
                        $CIDING[$a] = $COM['CID'];
                    if($COM['UID'] === $_SESSION['ID'] or $_SESSION['UT'] === "master" or $_SESSION['UT'] === "mod"){
                        echo '
                            <form method="post">
                                <input type="hidden" name="PCID" value="' . $COM['CID'] . '"/>
                                <input type="submit" name="COMDEL" Value="DELETE"/>
                            </form>
                            ';
                            if($_POST['COMDEL']){
                                $CLD = $DBH->prepare('DELETE FROM BO_comments WHERE CID = ' . $_POST['PCID']);
                                $CLD->execute();
                                $COMD = $DBH->prepare('DELETE FROM BO_commenter WHERE CID = ' . $_POST['PCID']);
                                $COMD->execute();
                                $DELETED = 'true';
                                break;
                            };
                        };
                    };
                    if($DELETED === 'true'){
                        redirect('show.php?post=' . $_GET['post']);
                    };
                    ?>
                </div>
            </div>
            
        </main>

    </body>

    </html>