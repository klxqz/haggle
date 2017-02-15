<?php

class shopHaggleHelper {

    public static function getTemplateUrl($template_id) {
        $template = self::getTemplate($template_id, false);
        $template_url = '';

        if ($template['tpl_ext'] == 'css') {
            $css_content = file_get_contents($template['template_path']);
            $css_content = str_replace('{$wa_url}', wa()->getRootUrl(), $css_content);
            $tpl_full_path = $template['tpl_path'] . 'tmp' . '.' . $template['tpl_name'] . '.' . $template['tpl_ext'];
            $template_path = wa()->getDataPath($tpl_full_path, $template['public'], 'shop', true);
            $f = fopen($template_path, 'w');
            if (!$f) {
                throw new waException('Не удаётся сохранить шаблон. Проверьте права на запись ' . $template_path);
            }
            fwrite($f, $css_content);
            fclose($f);
            $template_url = wa()->getDataUrl($tpl_full_path, true, 'shop');
        } elseif ($template['tpl_ext'] == 'js') {
            if ($template['change_tpl']) {
                $template_url = wa()->getDataUrl($template['tpl_full_path'], true, 'shop');
            } else {
                $template_url = wa()->getAppStaticUrl() . $template['tpl_full_path'];
            }
        }

        return $template_url;
    }

    public static function getTemplates($template_id = null) {
        if ($template_id) {
            return self::getTemplate($template_id);
        } else {
            $templates = array();
            foreach (shopHagglePlugin::$templates as $template_id => $template) {
                $templates[$template_id] = self::getTemplate($template_id);
            }
            return $templates;
        }
    }

    protected static function getTemplate($template_id, $read = true) {
        if (empty(shopHagglePlugin::$templates[$template_id])) {
            return false;
        }

        $template = shopHagglePlugin::$templates[$template_id];

        $tpl_full_path = $template['tpl_path'] . $template['tpl_name'] . '.' . $template['tpl_ext'];
        $template_path = wa()->getDataPath($tpl_full_path, $template['public'], 'shop', true);
        if (file_exists($template_path)) {
            $template['change_tpl'] = 1;
        } else {
            $tpl_full_path = $template['tpl_path'] . $template['tpl_name'] . '.' . $template['tpl_ext'];
            $template_path = wa()->getAppPath($tpl_full_path, 'shop');
            $template['change_tpl'] = 0;
        }
        $template['tpl_full_path'] = $tpl_full_path;
        $template['template_path'] = $template_path;
        if ($read) {
            $template['template'] = file_get_contents($template_path);
        }
        return $template;
    }

}
