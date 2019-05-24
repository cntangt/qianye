		<!--底部信息-->
	<div class="clear blank10"></div>
    <div class="footer">
        <div class="footnav">
        <a href="<?php echo $site_url; ?>">首页</a>
<!--注意 当啥参数都不写的时候 nav后面需要跟两个空格-->
		<?php $return = $this->_category(" ");  if (is_array($return))  foreach ($return as $key=>$xiao) { $allchildids = @explode(',', $xiao['allchildids']);    $current = in_array($catid, $allchildids);?>
		<a href="<?php echo $xiao['url']; ?>"><?php echo $xiao['catname']; ?></a>
		<?php } ?>
			
        </div>  
        <div style="text-align:center">
		<form method="Get" action="index.php" >
		<input type="hidden"  value="index"  name="c" />
		<input type="hidden"  value="search"  name="a" />

		<input type="text"  value=""  name="kw" />
<!--搜索指定栏目		<input type="hidden"  value="1"  name="catid" />-->
		<input type="submit"   value="搜索展示"   />
        </form>
		</div>
       <div class="copyright">Powered by <a href="http://www.xiaocms.com" target="_blank">XiaoCms</a>  © 2014</div>
    </div>
</body>
</html>