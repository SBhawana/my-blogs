<?php
require_once './includes/header.php';
@include '../private.php';
$ck_editor_key = defined('CK_EDITOR_KEY') ? CK_EDITOR_KEY : 'nhi h';

// GET DATA FROM CATEGORY TABLE FOR CATEGORY NAME
$category = "SELECT * FROM `category` ";
$category_query = mysqli_query($con, $category);
$category_result = mysqli_fetch_all($category_query, MYSQLI_ASSOC);


$blog_name = "" ;
$blog_category = "";
$blog_content = "";
// CHECK EDIT OR NOT
if (isset($_POST['edit_id'])) {
    $edit_id = mysqli_real_escape_string($con, trim($_POST['edit_id']));

    $existed_blog = "SELECT * FROM `blogs` WHERE id = '$edit_id'";
    $existed_blog_sql = mysqli_query($con, $existed_blog);
    $existed_blog_sql_res = mysqli_fetch_assoc($existed_blog_sql);
    pr($existed_blog_sql_res);
    $blog_name = $existed_category_sql_res['blog_name'];
    $blog_category = $existed_category_sql_res['blog_category'];
    $blog_content = $existed_category_sql_res['blog_content'];
}
if (isset($_POST['submit'])) {
pr($_POST);
    $blog_name = mysqli_real_escape_string($con, trim($_POST['blog_name']));
    $blog_category = mysqli_real_escape_string($con, trim($_POST['blog_category']));
    $blog_content = htmlspecialchars(mysqli_real_escape_string($con, trim($_POST['blog_content'])));

    if ($blog_name === '') {
        $errors[] = 'Blog name cannot be blank ';
    }
    if ($blog_category === '') {
        $errors[] = 'Blog Category cannot be blank ';
    }
    if ($blog_content === '') {
        $errors[] = 'Blog Content cannot be blank ';
    }

    $existed_blog = "SELECT * FROM `blogs` WHERE blog_name = '$blog_name' AND blog_Category
    = '$blog_category' AND blog_content = '$blog_content' ";
    $existed_blog_sql = mysqli_query($con, $existed_blog);
pr($existed_blog_sql);

    if (mysqli_num_rows($existed_blog_sql) > 0) {
        $edit_data = mysqli_fetch_assoc($existed_blog_sql);
        if ($_POST['edit_id']) {
            $edit_data = mysqli_fetch_assoc($existed_blog_sql);

            if ($_POST['edit_id'] !== $edit_data['id']) {
                $errors[] = 'Already existed category name ';
            }
        } else {
            $errors[] = 'Already existed category name ';
        }
    }

    if (empty($errors)) {

        if ($_post['edit_id']) {
            echo $update_category = "UPDATE `blogs` SET blog_name = '$blog_name' WHERE id='" . $_POST['edit_id'] . "' ";
            $update_category_sql = mysqli_query($con, $update_category);
            pr($update_category_sql);
            $_SESSION['success'] = 'Blog Updated Successfully';
            header('location: blogs.php');
            die();
        } else {
            $insertblog = "INSERT INTO `blogs` (blog_name ,category_id ,blog_content ,created_at) VALUES ('$blog_name','$blog_category','$blog_content' , 'current_timestamp()')";
            $insertblog_query = mysqli_query($con, $insertblog);
            if ($insertblog_query) {
                $_SESSION['success'] = 'Blog Submitted Successfully !';
                header('location: index.php');
                die();
            } else {
                $errors[] = 'Something wrong';
            }
        }
    }
}
// INSRT DATA IN BLOGS
if (isset($_POST['submit'])) {
    $blog_name = mysqli_real_escape_string($con, trim($_POST['blog_name']));
    $blog_category = mysqli_real_escape_string($con, trim($_POST['blog_category']));
    $blog_content = htmlspecialchars(mysqli_real_escape_string($con, trim($_POST['blog_content'])));

    // $insertblog = "INSERT INTO `blogs` (blog_name ,category_id ,blog_content) VALUES ('$blog_name','$blog_category','$blog_content')";
    // $insertblog_query = mysqli_query($con, $insertblog);
    // if ($insertblog_query) {
    //     echo "Blog Submitted Successfully !";
    // }
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<script>
            toastr.error('$error');
        </script>";
    }
}
if (!empty($success)) {

    echo "<script>
            toastr.success('$success');
        </script>";
}
?>

<!-- CK EDITOR LINK -->
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css" />

<!-- HEADER OF THE PAGE -->
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">

                <h3 class="mb-0">Manage Blogs</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item btn btn-primary btn-lg">
                        <a href="blogs.php" class="text-white">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- FORM OF CONTENT  -->
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-md-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <div class="card-title">Add Blogs</div>
                </div>
                <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="blogName" class="form-label">Blog Name</label>
                            <input type="text" class="form-control" id="blogName" name="blog_name" value="<?= $blog_name ?>" autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="blogCategory" class="form-label">Blog Category</label>
                            <select class="form-select" id="blogCategory" name="blog_category" value="<?= $blog_category ?>" autofocus>
                                <option selected="" disabled="" value="">Choose...</option>
                                <?php
                                if (is_array($category_result) && count($category_result)) {
                                    foreach ($category_result as $key => $category_res) {
                                ?>
                                        <option><?= $category_res['category_name'] ?></option>
                                <?php }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="blogContent" class="form-label">Blog Content</label>
                            <textarea id="editor" name="blog_content" id="blog_content" value="<?= $blog_content ?>" autofocus>
                            </textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once './includes/footer.php';
?>


<script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js"></script>
<script>
    const {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Font,
        Paragraph,
        Heading,
        BlockQuote,
        Link,
        List,
        Alignment,
        Image,
        ImageUpload,
        Code,
        CodeBlock,
        Strikethrough,
        Subscript,
        Superscript,
        TodoList,

    } = CKEDITOR;

    ClassicEditor
        .create(document.querySelector('#editor'), {
            licenseKey: '<?= $ck_editor_key ?>',
            plugins: [
                Essentials, Bold, Italic, Font, Paragraph, Heading, BlockQuote,
                Link, List, Alignment, Image, ImageUpload, Code, CodeBlock,
                Strikethrough, Subscript, Superscript, TodoList
            ],

            toolbar: {
                items: [
                    'undo', 'redo',
                    '|',
                    'heading',
                    '|',
                    'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',
                    '|',
                    'bold', 'italic', 'strikethrough', 'subscript', 'superscript', 'code',
                    '|',
                    'link', 'uploadImage', 'blockQuote', 'codeBlock',
                    '|',
                    'alignment',
                    '|',
                    'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent'
                ],
                shouldNotGroupWhenFull: true
            },
        })
        .then(editor => {
            console.log("Editor loaded successfully", editor);
        })
        .catch(error => {
            console.error("Editor error:", error);
        });
</script>