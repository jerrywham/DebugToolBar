explain
=======

PHP method for debug


Méthode de debug PHP
 
*@param  var                la variable à analyser
*@param  html     bool      affiche la sortie au format html ou modifie les balises html par leur équivalent unicode
*@param  return   bool      affiche ou non le résultat qui peut ainsi être récupéré dans une variable
*@param  sub      integer   affiche le résultat entre des balises pre
*@param  way      string    sens de lecture du tableau debug_backtrace
*@return          string    le résultat
* 
*@author unknown, JeromeJ, Cyril MAGUIRE

How to use
----

Importer le fichier en début de script en faisant :
require 'explain.php';

explain($MyVar);
explain($MyVar,true);
$result = explain($MyVar,false,true);
explain($MyVar,false,false,1);

Shortcuts

e($MyVar); produit le même résultat que :       explain($MyVar);
ed($MyVar); produit le même résultat que :      explain($MyVar);exit(); 
