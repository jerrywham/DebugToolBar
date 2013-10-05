DebugToolBar
============

Barre de debug disponible sur toutes les pages (publiques et privées). Elle s'affiche en bas de page.
Toutes les variables globales sont disponibles par défaut ($_POST, $_GET, $_FILES, $_SESSION, $_COOKIE, $_REQUEST).

Pour la rendre fonctionnelle, il faut 
* inclure la classe en haut de page
		
		include_once(path/to/debugToolBar.php');

* ajouter la ligne suivante juste avant la balise &lt;/body&gt;

		Debug::getInstance()->printBar();
		

Exploration d'une variable
--------------------------

Il est possible également de voir le contenu d'une variable. Pour cela, il faut appeler la méthode statique trac().
Deux paramètres sont à saisir : le premier obligatoire est la variable à explorer, le deuxième optionnel est le message
que l'on souhaite afficher (généralement, le nom de la variable entre guillemets).

        Debug::tac($var,'$var');
        
Il existe un raccourci pour cette méthode. Ainsi, on obtiendra le même résultat que précédemment en faisant :
    
        d($var,'$var');
        

Exploration d'une variable dans une boucle
------------------------------------------

On utilisera pour cela la méthode flow() qui évite l'arrêt du script.

        foreach($var as $k => $v) {
            Debug::flow($v);
        }
        

Un raccourci existe également pour cette méthode :

        foreach($var as $k => $v) {
            f($v);
        }
