<?php

class DemoController extends Controller {
    public function showView() {
        // 演示基本视图渲染
        $this->view('demo/index', [
            'title' => 'Luminode框架演示',
            'features' => [
                '轻量级设计',
                'MVC架构',
                '简单路由系统',
                '基础ORM支持',
                '模板引擎'
            ]
        ]);
    }

    public function showDbDemo() {
        // 演示数据库操作
        $posts = $this->db->fetchAll("SELECT * FROM posts LIMIT 5");
        $this->view('demo/db', ['posts' => $posts]);
    }

    public function showFormDemo() {
        // 演示表单处理
        $data = [];
        if ($this->request['method'] === 'POST') {
            $data = $this->request['input'];
            // 简单验证
            if (empty($data['name'])) {
                $data['error'] = '姓名不能为空';
            } else {
                $data['success'] = '表单提交成功';
            }
        }
        $this->view('demo/form', $data);
    }

    public function showTemplateDemo() {
        // 演示模板功能
        $this->setLayout('demo_layout');
        $this->view('demo/template', [
            'sectionTitle' => '模板引擎演示',
            'content' => '这是通过模板引擎渲染的内容'
        ]);
    }
}
