echo '开始推送github'
git remote rm origin
git remote add origin git@github.com:huagelong/eduxplus.git
echo '设置上传代码分支，推送github'
git push --set-upstream origin master --force
echo '推送github完成'

echo '开始推送dokku'
git remote rm origin
git remote add origin dokku@dev.eduxplus.com:dev-eduxplus
echo '设置上传代码分支，推送dokku'
git push origin master
echo '推送dokku完成'

echo '开始推送gitee'
git remote rm origin
git remote add origin git@gitee.com:huagelong/eduxplus.git
git push origin master

echo '切回gitee资源'
git branch --set-upstream-to=origin/master master
git push
echo '设置git跟踪资源'
