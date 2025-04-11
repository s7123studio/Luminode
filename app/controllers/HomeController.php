<?php

class HomeController extends Controller {
    public function index() {
        try {
            $this->db->beginTransaction();
            
            $data = [
                'posts' => $this->db->fetchAll("SELECT * FROM posts ORDER BY created_at DESC"),
                'settings' => $this->getSettings()
            ];
            
            $this->db->commit();
            $this->view('home', $data);
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function showPost($id) {
        try {
            $post = $this->db->fetch("SELECT * FROM posts WHERE id = ?", [$id]);
            if (!$post) {
                throw new RuntimeException('Post not found');
            }
            $this->view('post', ['post' => $post]);
        } catch (Exception $e) {
            $this->json(['error' => $e->getMessage()], 404);
        }
    }
    
    private function getSettings() {
        $settings = [];
        $keys = [
            'sitename', 'site_favicon', 'site_description', 'avatar_link',
            'signature', 'background_link', 'Copyright_date', 'Copyright_name',
            'Copyright_Customize'
        ];
        
        foreach ($keys as $key) {
            $result = $this->db->fetch("SELECT value FROM settings WHERE `key` = ?", [$key]);
            $settings[$key] = $result['value'] ?? '';
        }
        
        // 处理链接
        for ($i = 1; $i <= 5; $i++) {
            $linkValue = $this->db->fetch("SELECT value FROM settings WHERE `key` = ?", ["link_$i"])['value'] ?? '';
            $linkName = $this->db->fetch("SELECT value FROM settings WHERE `key` = ?", ["link_{$i}_name"])['value'] ?? '';
            
            if ($linkValue) {
                $settings["link_$i"] = $this->generateLinkHtml($linkValue, $linkName);
            }
        }
        
        // 处理社交链接
        $socialLinks = ['QQ', 'Mail', 'Money', 'Github', 'Gitee', 'BiLiBiLi', 'Coolapk'];
        foreach ($socialLinks as $social) {
            $value = $this->db->fetch("SELECT value FROM settings WHERE `key` = ?", ["{$social}_link"])['value'] ?? '';
            if ($value) {
                $settings["{$social}_link"] = $this->generateSocialLinkHtml($social, $value);
            }
        }
        
        return $settings;
    }
    
    private function generateLinkHtml($url, $name) {
        if (empty($url)) return '';
        
        $html = $name ? 
            "<a href=\"$url\"class=\"link-item\"><span class=\"holographic-effect\"></span><span class=\"blur-layer\"></span>
            <span class=\"harmony-glow\"></span><span class=\"label\">$name</span></a>" : 
            "<a href=\"$url\"class=\"link-item\"><span class=\"holographic-effect\"></span><span class=\"blur-layer\"></span>
            <span class=\"harmony-glow\"></span>";
            
        // 标记为安全HTML，避免被转义
        return (object)['safe_html' => true, 'value' => $html];
    }
    
    private function generateSocialLinkHtml($social, $url) {
        if (empty($url)) return '';
        
        $icon = strtolower($social) . '.svg';
        $mailto = $social === 'Mail' ? 'mailto:' : '';
        $html = "<a class=\"social-item\" target=\"_blank\" href=\"{$mailto}{$url}\"> <img src=\"images/svg/$icon\" width=\"30\" alt=\"$social\" title=\"$social\" /></a>";
        
        // 标记为安全HTML，避免被转义
        return (object)['safe_html' => true, 'value' => $html];
    }
}
