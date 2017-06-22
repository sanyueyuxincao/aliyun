<?php


namespace App\Http\Controllers\v1_0\Url;


use App\Http\Controllers\AuthController;
use App\Models\Manager;
use App\Models\Users;
use Illuminate\Http\Request;


class UrlManagerController extends AuthController
{
    protected $manager;
    protected $users;
    public function __construct(Request $request,Manager $manager,Users $users)
    {
        parent::__construct($request);
        $this->manager = $manager;
        $this->users = $users;
    }

    /**
     * 添加模式
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function urlcreate(Request $request)
    {
        $user_info = $this->user_info;
        $userId = $user_info->userId;
        $data = $request->all();
        $data['userId'] = $userId;
		unset($data['token']);
        $res = $this->manager->create($data);
        return $this->apiResponse($res);
    }

    /**
     * 更新模式
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function urlupdate(Request $request)
    {
        $user_info = $this->user_info;
        $userId = $user_info->userId;
        $data = $request->all();
        $data['userId'] = $userId;
        unset($data['token']);
        $res = $this->manager->urlupdate($data);
        return $this->apiResponse($res);
    }

	public function urlquerykd(Request $request)
	{
		$user_info = $this->user_info;
		$userId = $user_info->userId;
		$keyword = $request->input('keyword','0');
		$res = $this->manager->urlquerykd($userId,$keyword);
		return $this->apiResponse($res);
	}

    public function urlquerypage(Request $request)
    {
        $user_info = $this->user_info;
        $userId = $user_info->userId;
        $page = $request->input('page','1');
        $res = $this->manager->urlquerypage($userId,$page);
        return $this->apiResponse($res);
    }

}
