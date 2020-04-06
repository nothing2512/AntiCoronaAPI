<?php /** @noinspection PhpUnused */
/** @noinspection PhpUnusedParameterInspection */

/** @noinspection PhpUndefinedMethodInspection */


use Corona\models\NewsModel;
use Corona\systems\Request;

$this->get("", News::class . ":getNews");

class News
{

    /**
     * @var NewsModel newsModel
     */
    private $newsModel;

    /**
     * @var Request
     */
    private $request;

    public function __construct()
    {
        $this->request = new Request();
        $this->newsModel = new NewsModel();
    }

    public function getNews($request, $response)
    {

        $this->request->parse($request);

        $result = [];
        $data = $this->newsModel->getNews($this->request->get("lang") == "eng" ? "us" : "id");

        foreach ($data->articles as $article) {

            array_push($result, [
                "author" => $article->author,
                "title" => $article->title,
                "image" => $article->urlToImage,
                "url"   => $article->url
            ]);
        }

        return $response->withJSON($result);
    }

}