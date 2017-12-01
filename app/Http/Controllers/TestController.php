<?php

/**
 * 会员管理
 * @filename  MemberController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2017-9-14 18:20:12
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public $M;

    public function __construct()
    {
       // parent::__construct();
       
    }
	
	public function index()
	{
	    //print_r($id . $name);

        // 获取没有查询字符串的当前的 URL ...
        echo url()->current() . '<br>';

// 获取包含查询字符串的当前的 URL ...
        echo url()->full() . '<br>';

// 获取上一个请求的完整的 URL...
        echo url()->previous() . '<br>';

        $url = action('TestController@index', ['ids' => 2]);

        echo $url . '<br>';

        if(request('id')){
            echo request('id') . '<br>';
        }
        $member = DB::select('select * from member where id = ?', [1]);
        return view('test', ['name' => 'James', 'member' => $member]);
	}

    //列表
    public function lists()
    {

        $where = [];

        if (request('realname')) {
            $where[] = ['realname', 'like', '%' . request('realname') . '%'];
        }
        if (request('mobile')) {
            $where[] = ['mobile', request('mobile')];
        }
        if (request('sex')) {
            $where[] = ['sex', request('sex')];
        }
        if (request('status')) {
            $where[] = ['status', request('status')];
        }

        $lists = $this->M->where($where)->orderBy('id', 'desc')->paginate(20);

        if (request()->ajax()) {
            return $lists;
        } else {
            return $this->view(compact('lists'));

        }

    }

    //详情
    public function info()
    {
        $info = $this->M->find(request('id'));
        return $this->view(compact('info'));

    }

    //添加
    public function add()
    {
        if ($this->storage()) {
            return $this->success('添加成功', '/' . $this->c . '/lists');
        } else {
            return $this->error();
        }
    }

    //修改
    public function edit()
    {
        if ($this->storage()) {
            return $this->success('修改成功', '/' . $this->c . '/lists');
        } else {
            return $this->error();
        }
    }

    /*
     * 存储
     */
    private function storage()
    {
        //$this->validate(request(), $this->M->rules, $this->M->messages);
        request()->validate([
            'info.realname' => [
                'required',
                'string',
                'max:100',
                'min:2',
            ],
            'info.mobile' => [
                'required',
                'regex:/1[3|8|4]{1}[\d]{9}$/',
                'unique:member,mobile,' . request("id")
            ]
        ],[
            'info.realname.required' => '名称不能为空',
            'info.mobile.required' => '手机号不能为空',
            'info.mobile.regex' => '手机号格式不正确',
            'info.mobile.unique' => '手机号已存在',
        ]);


        $data = request('info');


        if ($id = request('id')) {
            //修改
            $rs = $this->M->where('id', $id)->update($data);
        } else {
            //添加
            $data['agent_id'] = $this->login_user->id;
            $rs = $this->M->create($data);
        }

        return $rs;
    }


}
