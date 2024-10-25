<?php

use Ashkanfekridev\RadioJavan;

require_once __DIR__ . '/vendor/autoload.php';

$options = ['proxy' => 'socks5h://127.0.0.1:2080'];
//$options = [];
$media = [];

if (isset($_GET['url'])) {
    try {
        $radio = new RadioJavan($_GET['url'], $options);


        if ($radio->getType() === '') {
            throw new Exception("مشکل در واکشی اطلاعات از سمت سرور: لینک نا معتبر!");
        }

       /* echo json_encode($radio->getResponse());
           return;*/
        //   echo json_encode($radio->getArtist());
        $media['type'] = $radio->getType();

        switch ($media['type']):
            case 'video':
                $media['title'] = $radio->getMediaTitle();
                $media['photo'] = $radio->getMediaPhoto();
                $media['photo'] = $radio->getMediaThumbnail();
                $media['link'] = $radio->getMediaLink();
                break;
            case "podcast":
            case "song":
                $media['title'] = $radio->getMediaTitle();
                $media['photo'] = $radio->getMediaPhoto();
                $media['link'] = $radio->getMediaLink();
                break;
            case "artist":
                $media['title'] = $radio->getArtistName();
                $media['photo'] = $radio->getArtistPhoto_thumb();
                $media['albums'] = $radio->getArtistAlbums();
                $media['mp3s'] = $radio->getArtistMp3s();
                $media['videos'] = $radio->getArtistVideos();
                break;
            default:
                throw new Exception("لینک نا معتبر!");
        endswitch;

    } catch (Exception $exception) {
        return die($exception);
    }


}


?>

<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>


</head>
<body>
<div id="app">
    <div class="container mx-auto max-w-xl p-8 prose lg:prose-xl">
        <form action="/">
            <label for="url">لینک مورد نظر از رادیو جوان</label>
            <input type="text" id="url" name="url"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <button type="submit"
                    class="min-w-full mt-3 justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-slate-900 text-white hover:bg-slate-700 block">
                تبدیل
            </button>
        </form>

        <div>
            <!--video-->

            <?php if (isset($media['type']) && $media['type'] === 'video') {
                ?>
                <p><?= $media['title']; ?></p>
                <img src="<?= $media['photo']; ?>" class="min-w-full">
                <a href="<?= $media['link']; ?>"
                   class="text-center min-w-full mt-3 justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-slate-900 text-white hover:bg-slate-700 block">دانلود</a>
                <?php
            } ?>

            <!--song-->

            <?php if (isset($media['type']) && $media['type'] === 'song' ||  isset($media['type']) && $media['type'] === 'podcast') {
                ?>
                <p><?= $media['title']; ?></p>
                <img src="<?= $media['photo']; ?>" class="min-w-full">
                <a href="<?= $media['link']; ?>"
                   class="text-center min-w-full mt-3 justify-center rounded-lg text-sm font-semibold py-3 px-4 bg-slate-900 text-white hover:bg-slate-700 block">دانلود</a>
                <?php
            } ?>

            <!--artist-->


            <?php if (isset($media['type']) && $media['type'] === 'artist') {
                ?>
                <p><?= $media['title']; ?></p>
                <img src="<?= $media['photo']; ?>" class="min-w-full">


                <div>
                    <h3>Albums</h3>
                    <ul>
                        <?php foreach ($media['albums'] as $album): ?>
                            <li>
                                <a href="/?url=<?= $album->share_link; ?>"><?= $album->title; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div>
                    <h3>songs</h3>
                    <ul>
                        <?php foreach ($media['mp3s'] as $mp3): ?>
                            <li><a href="<?= $mp3->link; ?>"><?= $mp3->title; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div>
                    <h3>videos</h3>
                    <ul>
                        <?php foreach ($media['videos'] as $video): ?>
                            <li><a href="<?= $video->link; ?>"><?= $video->title; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>


            <?php } ?>
        </div>

    </div>
</div>
</body>
</html>