<?php

/*
 * This file is part of the huangro/laravel-payment.
 *
 * (c) robin <robin@v5club.cn>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Transformer.php.
 *
 * @author    robin <robin@v5club.cn>
 * @copyright 2016 robin <robin@v5club.cn>
 *
 * @link      https://github.com/huangro
 */
namespace TYWeChat\Staff;

use TYWeChat\Message\AbstractMessage;
use TYWeChat\Message\News;
use TYWeChat\Message\Text;

/**
 * Class Transformer.
 */
class Transformer
{
    /**
     * transform message to XML.
     *
     * @param array|string|AbstractMessage $message
     *
     * @return array
     */
    public function transform($message)
    {
        if (is_array($message)) {
            $class = News::class;
        } else {
            if (is_string($message)) {
                $message = new Text(['content' => $message]);
            }

            $class = get_class($message);
        }

        $handle = 'transform'.substr($class, strlen('TYWeChat\Message\\'));

        return method_exists($this, $handle) ? $this->$handle($message) : [];
    }

    /**
     * Transform text message.
     *
     * @return array
     */
    public function transformText(AbstractMessage $message)
    {
        return [
                'msgtype' => 'text',
                'text' => [
                           'content' => $message->get('content'),
                          ],
               ];
    }

    /**
     * Transform image message.
     *
     * @return array
     */
    public function transformImage(AbstractMessage $message)
    {
        return [
                'msgtype' => 'image',
                'image' => [
                            'media_id' => $message->get('media_id'),
                           ],
               ];
    }

    /**
     * Transform video message.
     *
     * @return array
     */
    public function transformVideo(AbstractMessage $message)
    {
        return [
                'msgtype' => 'video',
                'video' => [
                            'title' => $message->get('title'),
                            'media_id' => $message->get('media_id'),
                            'description' => $message->get('description'),
                            'thumb_media_id' => $message->get('thumb_media_id'),
                           ],
               ];
    }

    /**
     * Transform voice message.
     *
     * @return array
     */
    public function transformVoice(AbstractMessage $message)
    {
        return [
                'msgtype' => 'voice',
                'voice' => [
                            'media_id' => $message->get('media_id'),
                           ],
               ];
    }

    /**
     * Transform articles message.
     *
     * @return array
     */
    public function transformNews($news)
    {
        $articles = [];

        if (!is_array($news)) {
            $news = [$news];
        }

        foreach ($news as $item) {
            $articles[] = [
                           'title' => $item->get('title'),
                           'description' => $item->get('description'),
                           'url' => $item->get('url'),
                           'picurl' => $item->get('pic_url'),
                          ];
        }

        return ['msgtype' => 'news', 'news' => ['articles' => $articles]];
    }

    /**
     * Transform material message.
     *
     * @return array
     */
    public function transformMaterial(AbstractMessage $message)
    {
        $type = $message->getType();

        return [
                'msgtype' => $type,
                $type => [
                            'media_id' => $message->get('media_id'),
                           ],
               ];
    }
}
