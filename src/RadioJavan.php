<?php

namespace Ashkanfekridev;

use src\Media\Video;

class RadioJavan
{

    private $response;

    private $mediaObjects = [
        'video' => Video::class
    ];

    public function __construct(
        private $url,
        private $options = [],
        $dev = false
    ) {
        if ($dev)
            return;
        $this->request();

        $this->responseNormalize();

        //if (array_key_exists($this->getType(), $this->mediaObjects)){
        //    return new $this->mediaObjects[$this->getType()]($this);
        //}
    }

    private function request(): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (isset($this->options['proxy']))
            curl_setopt($ch, CURLOPT_PROXY, $this->options['proxy']);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36");

        $this->response = curl_exec($ch);
        curl_close($ch);
    }

    private function responseNormalize(): void
    {

        preg_match("/<script id=\"__NEXT_DATA__\" type=\"application\/json\">(.*)<\/script>/s", $this->response, $this->response);
        $this->response = str_replace([
            '<script id="__NEXT_DATA__" type="application/json">',
            '</script>'
        ], ['', ''], $this->response[0]);

        $this->response = json_decode($this->response);

        $fp = fopen('clone.json', 'w');
        fwrite($fp, json_encode($this->response));
        fclose($fp);
    }

    public function setResponse($r)
    {
        $this->response = json_decode($r);
    }

    public function getResponse()
    {
        return $this->response;
    }


    public function getType()
    {
        return explode("/", $this->getPageUrl())[1];
    }

    function getPageUrl()
    {
        return $this->response->page;
    }


    // media

    function getMediaLink()
    {
        return $this->response->props->pageProps->media->link;
    }

    public function getMediaTitle()
    {
        return $this->response->props->pageProps->media->title;
    }

    public function getMediaArtist()
    {
        return $this->response->props->pageProps->media->artist;
    }

    public function getMediaSong()
    {
        return $this->response->props->pageProps->media->song;
    }

    public function getMediaThumbnail()
    {
        return $this->response->props->pageProps->media->thumbnail;
    }


    public function getMediaPhoto()
    {
        return $this->response->props->pageProps->media->photo;
    }


    public function getMediaType()
    {
        return $this->response->props->pageProps->media->type;
    }

    public function getMediaLyric()
    {
        return $this->response->props->pageProps->media->lyric;
    }

    public function getMediaLyricSynced()
    {
        return $this->response->props->pageProps->media->lyric_synced;
    }

    public function getMediaCreatedAt()
    {
        return $this->response->props->pageProps->media->created_at;
    }

    public function getMediaArtistFarsi()
    {
        return $this->response->props->pageProps->media->artist_farsi;
    }

    public function getMediaSongFarsi()
    {
        return $this->response->props->pageProps->media->song_farsi;
    }


    public function getPlayListTitle()
    {
        return $this->response->props->pageProps->playlist->title;
    }

    public function getPlayListItems()
    {
        return $this->response->props->pageProps->playlist->items;
    }


    public function getArtist()
    {
        return $this->response->props->pageProps->artist;
    }
    public function getArtistAlbums()
    {
        return $this->getArtist()->albums;
    }
    public function getArtistMp3s()
    {
        return $this->getArtist()->mp3s;
    }
    public function getArtistVideos()
    {
        return $this->getArtist()->videos;
    }

    public function getArtistPodcasts()
    {
        return $this->getArtist()->podcasts;
    }

    public function getArtistPlaylists()
    {
        return $this->getArtist()->playlists;
    }
    public function getArtistPhotos()
    {
        return $this->getArtist()->photos;
    }

    public function getArtistPhoto_thumb()
    {
        return $this->getArtist()->photo_thumb;
    }

    public function getArtistArtist_farsi()
    {
        return $this->getArtist()->artist_farsi;
    }

    public function getArtistName()
    {
        return $this->getArtist()->name;
    }
}
