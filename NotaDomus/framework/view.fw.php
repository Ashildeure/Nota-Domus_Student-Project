<?php

/**
 * Classe minimaliste pour normaliser l'usage d'une vue<br>
 * Cette classe est inspiré du moteur et compilateur de template Smarty
 */
class View {
    /**
     * @var array Paramètres de la vue, dans un tableau associatif
     */
    private array $param;

    // Constructeur d'une vue
    public function __construct() {
        // Initialise un tableau vide de paramètres
        $this->param = [];
    }


    /**
     * Ajoute un paramètre à la vue qui sera ensuite ré-utilisé par celle-ci.
     * Il n'y a aucune contrainte de type pour la valeur. Cela peut être par exemple un objet du modèle.
     * @param string $varName l'identificateur de la variable
     * @param mixed $value la valeur à lui donner
     * @return void
     */
    public function assign(string $varName, mixed $value): void {
        $this->param[$varName] = $value;
    }

    /**
     * Affiche la vue dont le nom est donné en argument
     * @param string $view le nom de la vue à afficher, sans ".view.php"
     * @return never
     */
    public function display(string $view): never {

        // Ajoute le chemin relatif vers le fichier de la vue
        // ATTENTION: pour éviter de créer une variable locale $filePath qui risque
        // de se collisionner avec les variables de la vue, utilise un attribut de l'objet
        $filePath = "view/$view.view.php";

        // Tous les paramètres de $this->param sont dupliqués en des variables
        // locales à la fonction display. Cela simplifie l'expression des
        // valeurs de la vue. Il faut simplement utiliser <?= $variable

        // Parcourt touts les paramètres de la vue
        foreach ($this->param as $key => $value) {
            // La notation $$ désigne une variable dont le nom est dans une autre variable
            // Cela crée une variable locale dont le nom est dans la variable $key
            $$key = $value;
        }

        // Inclusion de la vue
        // Comme cette inclusion est dans la portée de la méthode display alors
        // seules les variables locales à display sont visibles.
        require __DIR__ . '/../' . $filePath;
        // var_dump($this->filePath);
        // Apres cela le PHP est terminé, plus rien ne s'exécute
        exit(0);
    }

    /**
     * Affiche toutes les valeurs des paramètres de la vue
     * @return void
     */
    public function dump(): void {
        foreach ($this->param as $key => $value) {
            print "<br/><b>$key: </b>\n<pre>";
            var_dump($value);
            print '</pre>';
        }
    }
}
