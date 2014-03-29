<?php
    /**
     * @brief div 또는 span에 member_번호 가 있을때 해당 회원 번호에 맞는 이미지이름이나 닉이미지를 대체
     **/
    function memberTransImageName($matches) {

        // 회원번호를 추출하여 0보다 찾으면 본문중 text만 return
        $member_srl = $matches[3];
        if($member_srl<0) return $matches[5];

        $site_module_info = Context::get('site_module_info');
        $oMemberModel = &getModel('member');
        $group_image = $oMemberModel->getGroupImageMark($member_srl,$site_module_info->site_srl);

        // 회원이 아닐경우(member_srl = 0) 본문 전체를 return
        $nick_name = $matches[5];
        if(!$member_srl) return $matches[0];

        // 전역변수에 미리 설정한 데이터가 있다면 그걸 return
        if(!$GLOBALS['_transImageNameList'][$member_srl]->cached) {
            $GLOBALS['_transImageNameList'][$member_srl]->cached = true;
            $image_name_file = sprintf('files/member_extra_info/image_name/%s%d.gif', getNumberingPath($member_srl), $member_srl);
            $image_mark_file = sprintf('files/member_extra_info/image_mark/%s%d.gif', getNumberingPath($member_srl), $member_srl);
            if(file_exists($image_name_file)) $GLOBALS['_transImageNameList'][$member_srl]->image_name_file = $image_name_file;
            else $image_name_file = '';
            if(file_exists($image_mark_file)) $GLOBALS['_transImageNameList'][$member_srl]->image_mark_file = $image_mark_file;
            else $image_mark_file = '';
        }  else {
            $image_name_file = $GLOBALS['_transImageNameList'][$member_srl]->image_name_file;
            $image_mark_file = $GLOBALS['_transImageNameList'][$member_srl]->image_mark_file;
        }

        // 이미지이름이나 마크가 없으면 원본 정보를 세팅
        if(!$image_name_file && !$image_mark_file && !$group_image) return $matches[0];

        if($image_name_file) $nick_name = sprintf('<img src="%s%s" border="0" alt="id: %s" title="id: %s" style="vertical-align:middle;margin-right:3px" />', Context::getRequestUri(),$image_name_file, strip_tags($nick_name), strip_tags($nick_name));
        if($image_mark_file) $nick_name = sprintf('<img src="%s%s" border="0" alt="id: %s" title="id : %s" style="vertical-align:middle;margin-right:3px"/>%s', Context::getRequestUri(),$image_mark_file, strip_tags($nick_name), strip_tags($nick_name), $nick_name);

        if($group_image) $nick_name = sprintf('<img src="%s" border="0" style="vertical-align:middle;margin-right:3px" alt="%s" title="%s" />%s', $group_image->src, $group_image->title, $group_image->description, $nick_name);


        $orig_text = preg_replace('/'.preg_quote($matches[5],'/').'<\/'.$matches[6].'>$/', '', $matches[0]);
        return $orig_text.$nick_name.'</'.$matches[6].'>';
    }
?>
