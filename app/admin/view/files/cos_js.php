
<script type="text/javascript">
    !function(){"use strict";function t(t){return encodeURIComponent(t).replace(/!/g,"%21").replace(/'/g,"%27").replace(/\(/g,"%28").replace(/\)/g,"%29").replace(/\*/g,"%2A")}var e=e||function(t,e){var n={},r=n.lib={},i=function(){},s=r.Base={extend:function(t){i.prototype=this;var e=new i;return t&&e.mixIn(t),e.hasOwnProperty("init")||(e.init=function(){e.$super.init.apply(this,arguments)}),e.init.prototype=e,e.$super=this,e},create:function(){var t=this.extend();return t.init.apply(t,arguments),t},init:function(){},mixIn:function(t){for(var e in t)t.hasOwnProperty(e)&&(this[e]=t[e]);t.hasOwnProperty("toString")&&(this.toString=t.toString)},clone:function(){return this.init.prototype.extend(this)}},o=r.WordArray=s.extend({init:function(t,e){t=this.words=t||[],this.sigBytes=void 0!=e?e:4*t.length},toString:function(t){return(t||c).stringify(this)},concat:function(t){var e=this.words,n=t.words,r=this.sigBytes;if(t=t.sigBytes,this.clamp(),r%4)for(var i=0;i<t;i++)e[r+i>>>2]|=(n[i>>>2]>>>24-i%4*8&255)<<24-(r+i)%4*8;else if(65535<n.length)for(i=0;i<t;i+=4)e[r+i>>>2]=n[i>>>2];else e.push.apply(e,n);return this.sigBytes+=t,this},clamp:function(){var e=this.words,n=this.sigBytes;e[n>>>2]&=4294967295<<32-n%4*8,e.length=t.ceil(n/4)},clone:function(){var t=s.clone.call(this);return t.words=this.words.slice(0),t},random:function(e){for(var n=[],r=0;r<e;r+=4)n.push(4294967296*t.random()|0);return new o.init(n,e)}}),a=n.enc={},c=a.Hex={stringify:function(t){var e=t.words;t=t.sigBytes;for(var n=[],r=0;r<t;r++){var i=e[r>>>2]>>>24-r%4*8&255;n.push((i>>>4).toString(16)),n.push((15&i).toString(16))}return n.join("")},parse:function(t){for(var e=t.length,n=[],r=0;r<e;r+=2)n[r>>>3]|=parseInt(t.substr(r,2),16)<<24-r%8*4;return new o.init(n,e/2)}},h=a.Latin1={stringify:function(t){var e=t.words;t=t.sigBytes;for(var n=[],r=0;r<t;r++)n.push(String.fromCharCode(e[r>>>2]>>>24-r%4*8&255));return n.join("")},parse:function(t){for(var e=t.length,n=[],r=0;r<e;r++)n[r>>>2]|=(255&t.charCodeAt(r))<<24-r%4*8;return new o.init(n,e)}},u=a.Utf8={stringify:function(t){try{return decodeURIComponent(escape(h.stringify(t)))}catch(t){throw Error("Malformed UTF-8 data")}},parse:function(t){return h.parse(unescape(encodeURIComponent(t)))}},f=r.BufferedBlockAlgorithm=s.extend({reset:function(){this._data=new o.init,this._nDataBytes=0},_append:function(t){"string"==typeof t&&(t=u.parse(t)),this._data.concat(t),this._nDataBytes+=t.sigBytes},_process:function(e){var n=this._data,r=n.words,i=n.sigBytes,s=this.blockSize,a=i/(4*s),a=e?t.ceil(a):t.max((0|a)-this._minBufferSize,0);if(e=a*s,i=t.min(4*e,i),e){for(var c=0;c<e;c+=s)this._doProcessBlock(r,c);c=r.splice(0,e),n.sigBytes-=i}return new o.init(c,i)},clone:function(){var t=s.clone.call(this);return t._data=this._data.clone(),t},_minBufferSize:0});r.Hasher=f.extend({cfg:s.extend(),init:function(t){this.cfg=this.cfg.extend(t),this.reset()},reset:function(){f.reset.call(this),this._doReset()},update:function(t){return this._append(t),this._process(),this},finalize:function(t){return t&&this._append(t),this._doFinalize()},blockSize:16,_createHelper:function(t){return function(e,n){return new t.init(n).finalize(e)}},_createHmacHelper:function(t){return function(e,n){return new l.HMAC.init(t,n).finalize(e)}}});var l=n.algo={};return n}(Math);!function(){var t=e,n=t.lib,r=n.WordArray,i=n.Hasher,s=[],n=t.algo.SHA1=i.extend({_doReset:function(){this._hash=new r.init([1732584193,4023233417,2562383102,271733878,3285377520])},_doProcessBlock:function(t,e){for(var n=this._hash.words,r=n[0],i=n[1],o=n[2],a=n[3],c=n[4],h=0;80>h;h++){if(16>h)s[h]=0|t[e+h];else{var u=s[h-3]^s[h-8]^s[h-14]^s[h-16];s[h]=u<<1|u>>>31}u=(r<<5|r>>>27)+c+s[h],u=20>h?u+(1518500249+(i&o|~i&a)):40>h?u+(1859775393+(i^o^a)):60>h?u+((i&o|i&a|o&a)-1894007588):u+((i^o^a)-899497514),c=a,a=o,o=i<<30|i>>>2,i=r,r=u}n[0]=n[0]+r|0,n[1]=n[1]+i|0,n[2]=n[2]+o|0,n[3]=n[3]+a|0,n[4]=n[4]+c|0},_doFinalize:function(){var t=this._data,e=t.words,n=8*this._nDataBytes,r=8*t.sigBytes;return e[r>>>5]|=128<<24-r%32,e[14+(r+64>>>9<<4)]=Math.floor(n/4294967296),e[15+(r+64>>>9<<4)]=n,t.sigBytes=4*e.length,this._process(),this._hash},clone:function(){var t=i.clone.call(this);return t._hash=this._hash.clone(),t}});t.SHA1=i._createHelper(n),t.HmacSHA1=i._createHmacHelper(n)}(),function(){var t=e,n=t.enc.Utf8;t.algo.HMAC=t.lib.Base.extend({init:function(t,e){t=this._hasher=new t.init,"string"==typeof e&&(e=n.parse(e));var r=t.blockSize,i=4*r;e.sigBytes>i&&(e=t.finalize(e)),e.clamp();for(var s=this._oKey=e.clone(),o=this._iKey=e.clone(),a=s.words,c=o.words,h=0;h<r;h++)a[h]^=1549556828,c[h]^=909522486;s.sigBytes=o.sigBytes=i,this.reset()},reset:function(){var t=this._hasher;t.reset(),t.update(this._iKey)},update:function(t){return this._hasher.update(t),this},finalize:function(t){var e=this._hasher;return t=e.finalize(t),e.reset(),e.finalize(this._oKey.clone().concat(t))}})}(),function(){var t=e,n=t.lib,r=n.WordArray;t.enc.Base64={stringify:function(t){var e=t.words,n=t.sigBytes,r=this._map;t.clamp();for(var i=[],s=0;s<n;s+=3)for(var o=e[s>>>2]>>>24-s%4*8&255,a=e[s+1>>>2]>>>24-(s+1)%4*8&255,c=e[s+2>>>2]>>>24-(s+2)%4*8&255,h=o<<16|a<<8|c,u=0;u<4&&s+.75*u<n;u++)i.push(r.charAt(h>>>6*(3-u)&63));var f=r.charAt(64);if(f)for(;i.length%4;)i.push(f);return i.join("")},parse:function(t){var e=t.length,n=this._map,i=n.charAt(64);if(i){var s=t.indexOf(i);-1!=s&&(e=s)}for(var o=[],a=0,c=0;c<e;c++)if(c%4){var h=n.indexOf(t.charAt(c-1))<<c%4*2,u=n.indexOf(t.charAt(c))>>>6-c%4*2;o[a>>>2]|=(h|u)<<24-a%4*8,a++}return r.create(o,a)},_map:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="}}();var n=function(t){var n=t.Pathname||"/",r=t.Expires,i="",s="",o=t.Bucket.match(/^(.+)-(\d+)$/);o&&(i=o[1],s=o[2]);var a=parseInt(Math.random()*Math.pow(2,32)),c=parseInt(Date.now()/1e3),h=c+(void 0===r?900:1*r||0),u="/"+s+"/"+i+encodeURIComponent(n).replace(/%2F/g,"/"),f="a="+s+"&b="+i+"&k="+t.SecretId+"&e="+h+"&t="+c+"&r="+a+"&f="+u,l=e.HmacSHA1(f,t.SecretKey),p=e.enc.Utf8.parse(f);return l.concat(p).toString(e.enc.Base64)},r=function(r){if(!r.SecretId)return console.error("missing param SecretId");if(!r.SecretKey)return console.error("missing param SecretKey");if("4.0"===r.Version)return n(r);r=r||{};var i=r.SecretId,s=r.SecretKey,o=(r.Method||"get").toLowerCase(),a=r.Query||{},c=r.Headers||{},h=r.Pathname||"/",u=r.Expires,f=function(t){var e=[];for(var n in t)t.hasOwnProperty(n)&&e.push(n);return e.sort(function(t,e){return t=t.toLowerCase(),e=e.toLowerCase(),t===e?0:t>e?1:-1})},l=function(e,n){var r,i,s,o=[],a=f(e);for(r=0;r<a.length;r++)i=a[r],s=void 0===e[i]||null===e[i]?"":""+e[i],i=n?t(i).toLowerCase():t(i),s=t(s)||"",o.push(i+"="+s);return o.join("&")},p=parseInt((new Date).getTime()/1e3)-1,d=p+(void 0===u?900:1*u||0),g=i,y=p+";"+d,v=p+";"+d,m=f(c).join(";").toLowerCase(),_=f(a).join(";").toLowerCase(),w=e.HmacSHA1(v,s).toString(),S=[o,h,l(a,!0),l(c,!0),""].join("\n"),B=["sha1",y,e.SHA1(S).toString(),""].join("\n");return["q-sign-algorithm=sha1","q-ak="+g,"q-sign-time="+y,"q-key-time="+v,"q-header-list="+m,"q-url-param-list="+_,"q-signature="+e.HmacSHA1(B,w).toString()].join("&")};"object"==typeof module?module.exports=r:window.CosAuth=r}();
</script>
<script>
    var Bucket = '';
    var Region = '';
    var protocol = location.protocol === 'https:' ? 'https:' : 'http:';
    var prefix = '';
    var uploadDir = '';
    var fileName = '';
    var filePath = '';
    // 对更多字符编码的 url encode 格式
    var camSafeUrlEncode = function (str) {
        return encodeURIComponent(str)
            .replace(/!/g, '%21')
            .replace(/'/g, '%27')
            .replace(/\(/g, '%28')
            .replace(/\)/g, '%29')
            .replace(/\*/g, '%2A');
    };
    var getAuthorization = function (options, callback) {
        var url = '<?php echo url('get_sts'); ?>';
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        $.get(url,function(res){
            var credentials = res.credentials;
            Bucket =res.bucket;
            Region =res.region;
            uploadDir = res.uploadDir;
            filePath = res.uploadDir + options.Pathname;
            var savefile  = '/' + res.uploadDir + options.Pathname;
            prefix = protocol + '//' + Bucket + '.cos.' + Region + '.myqcloud.com/';
            callback(null, {
                SecurityToken: credentials.sessionToken,
                Authorization: CosAuth({
                    SecretId: credentials.tmpSecretId,
                    SecretKey: credentials.tmpSecretKey,
                    Method: options.Method,
                    Pathname:savefile,
                })
            });
        },'json');
        return;

    };

    var filerom = function (){
        var d = new Date();
        var str = d.getFullYear().toString() + d.getMonth().toString() + d.getDate().toString()+ d.getHours().toString() + d.getMinutes().toString() + d.getSeconds().toString();
        return str;
    }

    // 上传文件
    var uploadFile = function (file, callback) {
        fileName = file.name;
        var suffix = file.name.substring(file.name.lastIndexOf(".")+1);
        var Key = filerom() + '.' + suffix; // 这里指定上传目录和文件名
        getAuthorization({Method: 'PUT', Pathname:  Key}, function (err, info) {
            if (err) {
                layui.alert(err);
                return;
            }
            var auth = info.Authorization;
            var SecurityToken = info.SecurityToken;
            var url = prefix + uploadDir + camSafeUrlEncode(Key).replace(/%2F/g, '/');
            console.log(uploadDir);
            console.log(url);
            var xhr = new XMLHttpRequest();
            xhr.open('PUT', url, true);
            xhr.setRequestHeader('Authorization', auth);
            SecurityToken && xhr.setRequestHeader('x-cos-security-token', SecurityToken);
            xhr.upload.onprogress = function (e) {
                console.log('上传进度 ' + (Math.round(e.loaded / e.total * 10000) / 100) + '%');
            };
            xhr.onload = function () {
                if (/^2\d\d$/.test('' + xhr.status)) {
                    var ETag = xhr.getResponseHeader('etag');
                    console.log(ETag);
                    callback(null, {'url': url, 'ETag': ETag});
                } else {
                    callback('文件 ' + Key + ' 上传失败，状态码：' + xhr.status);
                }
            };
            xhr.onerror = function () {
                callback('文件 ' + Key + ' 上传失败，请检查是否没配置 CORS 跨域规则');
            };
            xhr.send(file);
        });
    };
    $(function(){
        $("#submitBtn").click(function (){
            var file = document.getElementById('fileSelector').files[0];
            if (!file) {
                layer.alert('未选择上传文件');
                return;
            }
            file && uploadFile(file, function (err, data) {
                console.log(data);
                $.post('<?php echo url('new_imgs_save'); ?>',{file:filePath,name:fileName,source:'cos'});
                var msg = err ? err : ('上传成功' );
                layer.alert(msg);
            });
        })

    })

</script>