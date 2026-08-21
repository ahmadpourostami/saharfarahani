(function(){
'use strict';

document.addEventListener('DOMContentLoaded', function(){
    var modal = document.getElementById('sf-auth-modal');
    if (!modal) return;
    var form = document.getElementById('sf-auth-form');
    var mobileInput = document.getElementById('sf-auth-mobile');
    var codeInput = document.getElementById('sf-auth-code');
    var message = modal.querySelector('.sf-auth-message');
    var mobileStep = modal.querySelector('[data-step="mobile"]');
    var codeStep = modal.querySelector('[data-step="code"]');
    var back = document.getElementById('sf-auth-back');
    var mobile = '';

    function openModal(){
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden','false');
        document.body.classList.add('sf-auth-open');
        setTimeout(function(){ mobileInput.focus(); },50);
    }
    function closeModal(){
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden','true');
        document.body.classList.remove('sf-auth-open');
        message.textContent='';
    }
    function setMessage(text, success){
        message.textContent=text||'';
        message.classList.toggle('is-success',!!success);
    }
    function request(action, data, button){
        var body = new URLSearchParams(data);
        body.append('action',action);
        body.append('nonce',sfAuth.nonce);
        if(button){button.classList.add('is-loading');button.disabled=true;}
        return fetch(sfAuth.ajaxUrl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'},body:body.toString()})
            .then(function(r){return r.json();})
            .finally(function(){if(button){button.classList.remove('is-loading');button.disabled=false;}});
    }

    document.querySelectorAll('[data-sf-auth-open]').forEach(function(btn){btn.addEventListener('click',openModal);});
    modal.querySelectorAll('[data-sf-auth-close]').forEach(function(btn){btn.addEventListener('click',closeModal);});
    document.addEventListener('keydown',function(e){if(e.key==='Escape'&&modal.classList.contains('is-open'))closeModal();});
    back.addEventListener('click',function(){codeStep.classList.remove('is-active');mobileStep.classList.add('is-active');setMessage('');mobileInput.focus();});

    form.addEventListener('submit',function(e){
        e.preventDefault();
        var button = form.querySelector('.sf-auth-step.is-active .sf-auth-submit');
        if(!codeStep.classList.contains('is-active')){
            mobile = mobileInput.value.replace(/\D/g,'');
            request('sf_request_otp',{mobile:mobile},button).then(function(res){
                if(res.success){
                    setMessage(res.data.message,true);
                    mobileStep.classList.remove('is-active');codeStep.classList.add('is-active');codeInput.focus();
                }else{setMessage(res.data&&res.data.message?res.data.message:'ارسال کد انجام نشد.');}
            }).catch(function(){setMessage('خطایی در ارتباط با سرور رخ داد.');});
        }else{
            request('sf_verify_otp',{mobile:mobile,code:codeInput.value},button).then(function(res){
                if(res.success){setMessage(res.data.message,true);window.location.href=res.data.redirect||window.location.href;}
                else{setMessage(res.data&&res.data.message?res.data.message:'کد تایید صحیح نیست.');}
            }).catch(function(){setMessage('خطایی در ارتباط با سرور رخ داد.');});
        }
    });
});
}());
