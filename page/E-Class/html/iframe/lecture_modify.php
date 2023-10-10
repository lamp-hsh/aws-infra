<?php
    include "../../inc/eclassinfo.inc";
    include "../member/stud_prof_flag.php";

    session_start();
    if (isset($_SESSION["userid"])) {
        $id = $_SESSION['userid'];
        $flag = stud_or_prof($id);
        $title = $_POST["title"];
        $content = $_POST["content"];
        $num = $_GET['num'];
        $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if(mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

        $query = "SELECT * FROM lecture WHERE num='$num';";
        $bodselect = mysqli_query($connection, $query);
        $board = mysqli_fetch_array($bodselect);
        if ($title != '' && $content != '') {
            if($flag[0] == $board['writer'] && $flag[1] == "professor") {
                $tmpfile =  $_FILES['file_reUpload']['tmp_name'];
                $real_name = $_FILES['file_reUpload']['name'];
                $directory = "../board/lecture/" . $real_name;
                move_uploaded_file($tmpfile, $directory);

                $query = "UPDATE lecture SET title='$title', content='$content', file='$real_name' WHERE num=$num;";
                $bodupdate = mysqli_query($connection, $query);
                echo '<script>alert("수정 완료"); document.location.href="./lecture.php";</script>';
            } else {
                echo '<script>alert("권한이 없습니다."); document.location.href="./lecture.php";</script>';
            }
        }
    }
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../../css/notice_post.css" />
	<title>게시판</title>
</head>
<body>
<div class="container">
    <form action="lecture_modify.php?num=<?php echo $num; ?>" method="post" enctype="multipart/form-data">
        <table class="board">
            <tr>
                <th class='title'>제목</th>
                <th class='ttitle'><input name="title" rows="1" cols="55" value="<?php echo $board['title']; ?>" maxlength="50" required></input></th>
            </tr>
            <tr>
                <th class='title'>내용</th>
                <th class='ttitle'><textarea name="content" rows="10" cols="100" required><?php echo $board['content']; ?></textarea></th>
            </tr>
            <tr>
                <th class='title'>현재 자료</th>
                <th class='ttitle'><?php echo $board['file']; ?></th>
            </tr>
            <tr>
                <th class='title'>자료 수정</th>
                <th class='ttitle'><input type="file" name="file_reUpload"></th>
            </tr>
        </table>
        <div class='write-btn'>
            <button id='delete' type="submit">글 수정</button>
        </div>
    </form>
    <div class='write-btn'>
        <button id='list' onclick="location.href='./lecture.php'">전체 글</button>
    </div>
</div>
</body>
</html>