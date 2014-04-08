APP="Frontend"
MODULE="Tmpfile"
DATABASE="scientiavulgaria"
PWD="rj7@kAv;8d7_e(E6:m4-w&"

DirWeb="/var/www/scientiavulgaria/Web/Files/$MODULE"
DirHome="/home/scientiavulgaria/Files/$MODULE"


#Script général : construction de l'architecture 
sh "install-module.sh" $APP $MODULE $DATABASE $PWD

#Suite
if [ ! -e $DirHome ]; then
	mkdir $DirHome
	ln -s $DirHome $DirWeb
	chmod 611 -R $DirHome
fi



