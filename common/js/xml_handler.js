/**
 * @file   common/js/xml_handler.js
 * @brief  XE에서 ajax기능을 이용함에 있어 module, act를 잘 사용하기 위한 자바스크립트
 **/
var show_waiting_message=true;function xml2json(xml,tab,ignoreAttrib){var X={toObj:function(xml){var o={};if(xml.nodeType==1){if(ignoreAttrib&&xml.attributes.length)
for(var i=0;i<xml.attributes.length;i++)
o["@"+xml.attributes[i].nodeName]=(xml.attributes[i].nodeValue||"").toString();if(xml.firstChild){var textChild=0,cdataChild=0,hasElementChild=false;for(var n=xml.firstChild;n;n=n.nextSibling){if(n.nodeType==1)hasElementChild=true;else if(n.nodeType==3&&n.nodeValue.match(/[^ \f\n\r\t\v]/))textChild++;else if(n.nodeType==4)cdataChild++;}
if(hasElementChild){if(textChild<2&&cdataChild<2){X.removeWhite(xml);for(var n=xml.firstChild;n;n=n.nextSibling){if(n.nodeType==3)
o=X.escape(n.nodeValue);else if(n.nodeType==4)
o=X.escape(n.nodeValue);else if(o[n.nodeName]){if(o[n.nodeName]instanceof Array)
o[n.nodeName][o[n.nodeName].length]=X.toObj(n);else
o[n.nodeName]=[o[n.nodeName],X.toObj(n)];}
else
o[n.nodeName]=X.toObj(n);}}
else{if(!xml.attributes.length)
o=X.escape(X.innerXml(xml));else
o["#text"]=X.escape(X.innerXml(xml));}}
else if(textChild){if(!xml.attributes.length)
o=X.escape(X.innerXml(xml));else
o["#text"]=X.escape(X.innerXml(xml));}
else if(cdataChild){if(cdataChild>1)
o=X.escape(X.innerXml(xml));else
for(var n=xml.firstChild;n;n=n.nextSibling){o=X.escape(n.nodeValue);}}}
if(!xml.attributes.length&&!xml.firstChild)o=null;}
else if(xml.nodeType==9){o=X.toObj(xml.documentElement);}
else
alert("unhandled node type: "+xml.nodeType);return o;},toJson:function(o,name,ind){var json=name?("\""+name+"\""):"";if(o instanceof Array){for(var i=0,n=o.length;i<n;i++)
o[i]=X.toJson(o[i],"",ind+"\t");json+=(name?":[":"[")+(o.length>1?("\n"+ind+"\t"+o.join(",\n"+ind+"\t")+"\n"+ind):o.join(""))+"]";}
else if(o==null)
json+=(name&&":")+"null";else if(typeof(o)=="object"){var arr=[];for(var m in o)
arr[arr.length]=X.toJson(o[m],m,ind+"\t");json+=(name?":{":"{")+(arr.length>1?("\n"+ind+"\t"+arr.join(",\n"+ind+"\t")+"\n"+ind):arr.join(""))+"}";}
else if(typeof(o)=="string")
json+=(name&&":")+"\""+o.toString()+"\"";else
json+=(name&&":")+o.toString();return json;},innerXml:function(node){var s=""
if("innerHTML"in node)
s=node.innerHTML;else{var asXml=function(n){var s="";if(n.nodeType==1){s+="<"+n.nodeName;for(var i=0;i<n.attributes.length;i++)
s+=" "+n.attributes[i].nodeName+"=\""+(n.attributes[i].nodeValue||"").toString()+"\"";if(n.firstChild){s+=">";for(var c=n.firstChild;c;c=c.nextSibling)
s+=asXml(c);s+="</"+n.nodeName+">";}
else
s+="/>";}
else if(n.nodeType==3)
s+=n.nodeValue;else if(n.nodeType==4)
s+="<![CDATA["+n.nodeValue+"]]>";return s;};for(var c=node.firstChild;c;c=c.nextSibling)
s+=asXml(c);}
return s;},escape:function(txt){return txt.replace(/[\\]/g,"\\\\").replace(/[\"]/g,'\\"').replace(/[\n]/g,'\\n').replace(/[\r]/g,'\\r');},removeWhite:function(e){e.normalize();for(var n=e.firstChild;n;){if(n.nodeType==3){if(!n.nodeValue.match(/[^ \f\n\r\t\v]/)){var nxt=n.nextSibling;e.removeChild(n);n=nxt;}
else
n=n.nextSibling;}
else if(n.nodeType==1){X.removeWhite(n);n=n.nextSibling;}
else
n=n.nextSibling;}
return e;}};if(xml.nodeType==9)
xml=xml.documentElement;var json_obj=X.toObj(X.removeWhite(xml)),json_str;if(typeof(JSON)=='object'&&jQuery.isFunction(JSON.stringify)&&false){var obj={};obj[xml.nodeName]=json_obj;json_str=JSON.stringify(obj);return json_str;}else{json_str=X.toJson(json_obj,xml.nodeName,"");return"{"+(tab?json_str.replace(/\t/g,tab):json_str.replace(/\t|\n/g,""))+"}";}}
(function($){$.exec_xml=window.exec_xml=function(module,act,params,callback_func,response_tags,callback_func_arg,fo_obj){var xml_path=request_uri+"index.php"
if(!params)params={};if($.isArray(params))params=arr2obj(params);params['module']=module;params['act']=act;if(typeof(xeVid)!='undefined')params['vid']=xeVid;if(typeof(response_tags)=="undefined"||response_tags.length<1)response_tags=['error','message'];else{response_tags.push('error','message');}
if($.isArray(ssl_actions)&&params['act']&&$.inArray(params['act'],ssl_actions)>=0)
{var url=default_url||request_uri;var port=window.https_port||443;var _ul=$('<a>').attr('href',url)[0];var target='https://'+_ul.hostname.replace(/:\d+$/,'');if(port!=443)target+=':'+port;if(_ul.pathname[0]!='/')target+='/';target+=_ul.pathname;xml_path=target.replace(/\/$/,'')+'/index.php';}
var _u1=$('<a>').attr('href',location.href)[0];var _u2=$('<a>').attr('href',xml_path)[0];if(_u1.protocol!=_u2.protocol||_u1.port!=_u2.port)return send_by_form(xml_path,params);var xml=[],i=0;xml[i++]='<?xml version="1.0" encoding="utf-8" ?>';xml[i++]='<methodCall>';xml[i++]='<params>';$.each(params,function(key,val){xml[i++]='<'+key+'><![CDATA['+val+']]></'+key+'>';});xml[i++]='</params>';xml[i++]='</methodCall>';var _xhr=null;if(_xhr&&_xhr.readyState!=0)_xhr.abort();function onsuccess(data,textStatus,xhr){var resp_xml=$(data).find('response')[0],resp_obj,txt='',ret=[],tags={},json_str='';waiting_obj.css('visibility','hidden');if(!resp_xml){alert(_xhr.responseText);return null;}
json_str=xml2json(resp_xml,false,false);resp_obj=(typeof(JSON)=='object'&&$.isFunction(JSON.parse))?JSON.parse(json_str):eval('('+json_str+')');resp_obj=resp_obj.response;if(typeof(resp_obj)=='undefined'){ret['error']=-1;ret['message']='Unexpected error occured.';try{if(typeof(txt=resp_xml.childNodes[0].firstChild.data)!='undefined')ret['message']+='\r\n'+txt;}catch(e){};return ret;}
$.each(response_tags,function(key,val){tags[val]=true;});tags["redirect_url"]=true;tags["act"]=true;$.each(resp_obj,function(key,val){if(tags[key])ret[key]=val;});if(ret['error']!=0){if($.isFunction($.exec_xml.onerror)){return $.exec_xml.onerror(module,act,ret,callback_func,response_tags,callback_func_arg,fo_obj);}
alert(ret['message']||'error!');return null;}
if(ret['redirect_url']){location.href=ret['redirect_url'].replace(/&amp;/g,'&');return null;}
if($.isFunction(callback_func))callback_func(ret,response_tags,callback_func_arg,fo_obj);}
try{$.ajax({url:xml_path,type:'POST',dataType:'xml',data:xml.join('\n'),contentType:'text/plain',beforeSend:function(xhr){_xhr=xhr;},success:onsuccess,error:function(xhr,textStatus){waiting_obj.css('visibility','hidden');var msg='';if(textStatus=='parsererror'){msg='The result is not valid XML :\n-------------------------------------\n';if(xhr.responseText=="")return;msg+=xhr.responseText.replace(/<[^>]+>/g,'');}else{msg=textStatus;}
alert(msg);}});}catch(e){alert(e);return;}
var waiting_obj=$('#waitingforserverresponse');if(show_waiting_message&&waiting_obj.length){var d=$(document);waiting_obj.html(waiting_message).css({'top':(d.scrollTop()+20)+'px','left':(d.scrollLeft()+20)+'px','visibility':'visible'});}}
function send_by_form(url,params){var frame_id='xeTmpIframe';var form_id='xeVirtualForm';if(!$('#'+frame_id).length){$('<iframe name="%id%" id="%id%" style="position:absolute;left:-1px;top:1px;width:1px;height:1px"></iframe>'.replace(/%id%/g,frame_id)).appendTo(document.body);}
$('#'+form_id).remove();var form=$('<form id="%id%"></form>'.replace(/%id%/g,form_id)).attr({'id':form_id,'method':'post','action':url,'target':frame_id});params['xeVirtualRequestMethod']='xml';params['xeRequestURI']=location.href.replace(/#(.*)$/i,'');params['xeVirtualRequestUrl']=request_uri;$.each(params,function(key,value){$('<input type="hidden">').attr('name',key).attr('value',value).appendTo(form);});form.appendTo(document.body).submit();}
function arr2obj(arr){var ret={};for(var key in arr){if(arr.hasOwnProperty(key))ret[key]=arr[key];}
return ret;}
$.exec_json=function(action,data,func){if(typeof(data)=='undefined')data={};action=action.split(".");if(action.length==2){if(show_waiting_message){$("#waitingforserverresponse").html(waiting_message).css('top',$(document).scrollTop()+20).css('left',$(document).scrollLeft()+20).css('visibility','visible');}
$.extend(data,{module:action[0],act:action[1]});if(typeof(xeVid)!='undefined')$.extend(data,{vid:xeVid});$.ajax({type:"POST",dataType:"json",url:request_uri,contentType:"application/json",data:$.param(data),success:function(data){$("#waitingforserverresponse").css('visibility','hidden');if(data.error>0)alert(data.message);if($.isFunction(func))func(data);}});}};$.fn.exec_html=function(action,data,type,func,args){if(typeof(data)=='undefined')data={};if(!$.inArray(type,['html','append','prepend']))type='html';var self=$(this);action=action.split(".");if(action.length==2){if(show_waiting_message){$("#waitingforserverresponse").html(waiting_message).css('top',$(document).scrollTop()+20).css('left',$(document).scrollLeft()+20).css('visibility','visible');}
$.extend(data,{module:action[0],act:action[1]});$.ajax({type:"POST",dataType:"html",url:request_uri,data:$.param(data),success:function(html){$("#waitingforserverresponse").css('visibility','hidden');self[type](html);if($.isFunction(func))func(args);}});}};})(jQuery);