
DB initial migration:
No need to run yii migration or migration for yii2-user, yii2-admin, yii2-upload separately. 
Instead, run

yii migrate/up --migrationPath=@vendor/azraf/yii2-simpleapp/migrations/initials

