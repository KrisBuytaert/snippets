cd /usr/local/bin
for value in `php ss_get_mysql_stats.php  --host localhost  --items th,b6,b7,cv,cx,cy,cz    --user root  --pass SARDINES   ` 
	do 
		split=`echo "mysql.$value" | sed -e "s/:/ /"`  
		echo "`uname -n`.$split `date +%s`" | nc 10.42.42.13 2003  
	done   
