<?php

namespace App\Helpers;


class FlashHelper
{
    public static function push($message, $level)
    {
        $arr = \Session::get($level, []);
        if (!is_array($arr)) { $arr = [ $arr ]; }
        $arr[] = $message;
        \Session::forget($level);
        \Session::flash($level, $arr);
    }

    public static function clear($level)
    {
        \Session::forget($level);
        \Session::flash($level, []);
    }

    public static function info($message)
    {
        self::push($message, 'info');
    }

    public static function error($message)
    {
        self::push($message, 'danger');
    }

    public static function danger($message)
    {
        self::push($message, 'danger');
    }

    public static function success($message)
    {
        self::push($message, 'success');
    }

    public static function overlay($message)
    {
        self::push($message, 'overlay');
    }

    public static function has($level)
    {
        return count( self::get($level) );
    }

    public static function get($level)
    {
        return \Session::get($level, []);
    }

    public static function hasOne()
    {
        return (
                    self::has('danger') ||
                    self::has('success') ||
                    self::has('info') ||
                    self::has('overlay') ||
                    self::has('warning')
        );
    }

    public static function render($level)
    {
        $arr = self::get($level);

        $html = "<div class=\"alert alert-{$level}\">";
        $html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        $html .= "<ul>";

        foreach ($arr as $message) {
            $html .= "<li>{$message}</li>";
        }

        $html .= "</ul></div>";
        return $html;
    }

    public static function clearAll() {
        self::clear('danger');
        self::clear('success');
        self::clear('info');
        self::clear('warning');
        self::clear('overlay');
    }

    public static function renderAll()
    {
        $result = '';
        if ( self::has('danger') ) $result .= self::render('danger');
        if ( self::has('warning') ) $result .= self::render('warning');
        if ( self::has('success') ) $result .= self::render('success');
        if ( self::has('info') ) $result .= self::render('info');
        if ( self::has('overlay') ) $result .= self::render('overlay');
        self::clearAll();
        return $result;
    }

    public static function renderAllJs() {
        $result  = '<script>';
        $result .= 'if (!window.growl_messages) {window.growl_messages = {error: [],notice: [],warning: []};}';
        foreach (self::get('danger') as $message) {
            $result .= 'window.growl_messages.error.push("' . $message . '");';
        }
        foreach (self::get('warning') as $message) {
            $result .= 'window.growl_messages.warning.push("' . $message . '");';
        }
        foreach (self::get('success') as $message) {
            $result .= 'window.growl_messages.notice.push("' . $message . '");';
        }
        foreach (self::get('info') as $message) {
            $result .= 'window.growl_messages.notice.push("' . $message . '");';
        }
        $result  .= '</script>';
        self::clearAll();
        return $result;
    }

}