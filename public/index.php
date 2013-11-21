<?php
// For demonstration PigLatin Translator purposes only. This isn't a valid representation of how a site should be loaded.
require_once '../src/application/bootloader.php';

$title = 'Pig Latin Translator';
$translation = new Classes\Translation\Translator($title);
$translation->setInputLanguage('English');
$translation->setOutputLanguage('PigLatin');
$translatedTitle = $translation->convert();

$text = '';
if(isset($_POST['text']) && $_POST['text'] != '') {
    $translation->setText($_POST['text']);
    $text = $translation->quickTranslate('English', 'PigLatin', $_POST['text']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) { ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <?php } ?>
    <title>FabienO - PigLatin Translator</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="static/css/normalize.min.css">
    <link rel="stylesheet" href="static/css/main.css">
    <link rel="stylesheet" media="only screen and (max-width: 730px)" href="static/css/mobile.css" />
    <link href='http://fonts.googleapis.com/css?family=Antic+Slab|Sintony' rel='stylesheet' type='text/css'>

    <script src="js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->
<div class="orange-bar"></div>

<div class="grey-bar">
    <div class="container">
        <div id="header">
            <h1><?php echo $title; ?></h1>
            <p><?php echo $translatedTitle; ?></p>
        </div>
    </div>
</div>

<div class="container">
    <div class="article">
        <div class="article-title">
            <h3>Input text</h3>
        </div>

        <div class="article-content">
            <div class="content top">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ;?>">
                    <textarea name="text" placeholder="sample text"><?php echo isset($_POST['text']) && $_POST['text'] != '' ? $_POST['text'] : ''; ?></textarea>
                    <input type="submit" value="translate" />
                </form>
            </div>
        </div>
    </div>

    <?php if(isset($text) && $text != '') { ?>
    <div class="article">
        <div class="article-title">
            <h3>Result</h3>
        </div>

        <div class="article-content">
            <div class="content top">
                <p><?php echo $text; ?></p>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
</body>
</html>
