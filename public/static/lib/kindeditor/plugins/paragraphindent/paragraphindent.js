/**
 * author:blog.clmao.com 
 * @param {type} param1
 * @param {type} param2
 */
KindEditor.plugin('paragraphindent', function(K) {
    var self = this, name = 'paragraphindent';
    self.clickToolbar(name, function() {
        var ti;
        function ChangeParentPTextindent(node) {
            var tname = node.tagName;
            if (tname == 'HTML' || tname == 'BODY') {
                var doc = self.edit.doc;
                var ps = doc.getElementsByTagName('p');
                for (var i = 0; i <= ps.length - 1; i++) {
                    ps[i].style.textIndent = "2em";
                }
                return null;
            }
            if (tname != 'P') {
                ChangeParentPTextindent(node.parentElement);
            } else {
                ti = node.style.textIndent.toString();
                node.style.textIndent = ti == ''? "2em":"";
            }
        }
        ChangeParentPTextindent(self.cmd.range.startContainer);
    });
});