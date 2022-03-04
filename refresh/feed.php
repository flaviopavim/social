<?php



$feed='<div class="feed">
<div class="feed-header">
    <div class="feed-title">Título</div>
    <i class="glyphicon glyphicon-remove"></i>
</div>
<div class="feed-body">
    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
    Donec eget nunc euismod, porttitor nunc eu,
    tincidunt nunc.
</div>
<div class="feed-footer">
    <a class="feed-like">Like</a>
    <a class="feed-comment">Comment</a>
    <a class="feed-share">Share</a>
</div>
</div>';


$html='';
for ($i=0;$i<10;$i++) {
    $html.=$feed;
}

//remover quebras de linhas
$html=preg_replace('/\s+/', ' ', $html);

//if ($_SESSION['last_html']!=$html) {
    //$_SESSION['last_html']=$html;
    ?>
    <script>
        $('#feed').html('<?php echo $html; ?>');
    </script>
<?php //}
