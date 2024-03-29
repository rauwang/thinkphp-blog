<?php

namespace app\admin\controller;

// 文章分类
class Category extends Common
{
    protected function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub

        $this->setTabs();
    }

    private function setTabs(array $tabs = [])
    {
        $this->assign('tabs', array_merge([
            '文章分类'  => url('/admin/category_list'),
            '添加分类'  => url('/admin/category_add'),
        ], $tabs));
    }

    public function table()
    {
        $this->assign('title', '文章分类');
        $this->assign('activeTab', '文章分类');

        $categorys = model('Category')->where('delete_time', null)->order('id', 'desc')->paginate(10);
        $this->assign('categorys', $categorys);
        return view();
    }

    public function add()
    {
        if (request()->isPost())
        {
            $result = model('Category')->add(input('post.'));
            if (1 == $result) $this->success('添加分类成功', '/admin/category_list');
            $this->assign('msg', $result);
        }
        $this->setTitle('添加分类');
        return view();
    }

    public function edit($id=0)
    {
        if (request()->isPost())
        {
            $result = model('Category')->edit($id, input('post.'));
            if (1 == $result)
            {
                $this->success('保存分类成功', '/admin/category_list');
            } $this->assign('msg', $result);
        }

        $category = model('Category')->where('delete_time', null)->find($id);

        if ($category) {
            $this->assign('category', $category);
        } else {
            $this->assign('msg', '不存在分类');
        }
        $this->setTabs(['编辑分类' => 'javascript:void(0)']);
        $this->setTitle('编辑分类');
        return view();
    }

    public function del($id)
    {
        $category = model('Category')->where('delete_time', null)->with('Article')->get($id);
        if (empty($category)) echo '不存在分类';
        else
        {
            if ($category->together('article')->delete()) $this->success('删除成功', '/admin/category_list');
            $this->error('删除失败', '/admin/category_list');
        }
    }

}
