#Version 1.0

if [ $1=="" || $2="" || $3="" || $4="" ]; then
	echo "Invalid app or module or database or password">"intall-log"
fi

APP=$1
MODULE=$2
DATABASE=$3
PWD=$4

Library="/var/www/scientiavulgaria/Library"
ModulePhp="/var/www/scientiavulgaria/Applications/$APP/Modules/$MODULE"

#Architecture
mkdir $ModulePhp
mkdir "$ModulePhp/Lib"
mkdir "$ModulePhp/Views"

#On deplace les fichiers php de lib
mv "Lib/*Builder.class.php" "$ModulePhp/Lib"	
mv "Lib/*Record.class.php" "$Library/Models/Records"
mv "Lib/*Manager.class.php" "$Library/Models/Managers"
mv "Lib/*.class.php" "$Library/Models/Objects" #il ne reste plus que les objets

#On deplace view
mv "Views" "$Library/Views"

#On deplace les fichiers généraux
mv "*Controller.class.php" $ModulePhp 
mv "config.xml" $ModulePhp

#Mysql
mysql -u root -p $PWD -D $DATABASE -e SOURCE $MODULE".sql"	





