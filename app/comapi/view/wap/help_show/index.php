
<head>
    <title>{$data.title}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
</head>

 <div class="w1200" style="background: #FFF;">
    <div class="news-detail">
      <div class="article-top">
        <div class="article-back">
        </div>
        <h3>{$data.title}</h3>
      </div>
      <div class="article-con" style="padding: 32px;">
        {$data.html|raw}
      </div>
    </div>
  </div>
  
  <style type="text/css">
  table{
    margin: 0 auto;
  }
  .w1200 {
    width:100%;
    margin: 0 auto;
}
  .article-top {
  margin: 0 0 25px;
  padding: 0 0 16px;
  border-bottom: 1px #e3e3e3 solid;
}
.article-back {
  text-align: right;
  line-height: 24px;
  font-size: 14px;
}
.article-back a {
  color: #f08519;
}
.article-top h3 {
  margin: 0 0 15px;
  text-align: center;
  font-weight: normal;
  font-size: 24px;
  color: #333;
}
.article-top p {
  text-align: center;
  font-size: 12px;
  color: #666;
}
.article-con {
  padding: 0 0 25px;
  margin: 0 0 20px;
  border-bottom: 1px #e3e3e3 solid;
  height: auto;
  line-height: 40px;
  font-size: 13px;
  color: #666;
}
.article-page span {
  display: block;
  width: 500px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.article-page span:last-child {
  text-align: right;
}
.article-page a {
  color: #666;
}
.article-bottom .article-back {
  text-align: center;
}

.news-detail {
  padding: 40px 0;
}

</style>