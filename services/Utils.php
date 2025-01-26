<?php

/**
 * Classe utilitaire : cette classe ne contient que des méthodes statiques qui peuvent être appelées
 * directement sans avoir besoin d'instancier un objet Utils.
 * Exemple : Utils::redirect('home'); 
 */
class Utils {
    /**
     * Convertit une date vers le format de type "Samedi 15 juillet 2023" en francais.
     * @param DateTime $date : la date à convertir.
     * @return string : la date convertie.
     */
    public static function convertDateToFrenchFormat(DateTime $date) : string
    {
        // Attention, s'il y a un soucis lié à IntlDateFormatter c'est qu'il faut
        // activer l'extention intl_date_formater (ou intl) au niveau du serveur apache. 
        // Ca peut se faire depuis php.ini ou parfois directement depuis votre utilitaire (wamp/mamp/xamp)
        $dateFormatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::FULL);
        $dateFormatter->setPattern('EEEE d MMMM Y');
        return $dateFormatter->format($date);
    }

    /**
     * Redirige vers une URL.
     * @param string $action : l'action que l'on veut faire (correspond aux actions dans le routeur).
     * @param array $params : Facultatif, les paramètres de l'action sous la forme ['param1' => 'valeur1', 'param2' => 'valeur2']
     * @return void
     */
    public static function redirect(string $action, array $params = []) : void
    {
        $url = "index.php?action=$action";
        foreach ($params as $paramName => $paramValue) {
            $url .= "&$paramName=$paramValue";
        }
        header("Location: $url");
        exit();
    }

    /**
     * Génère une URL pour le tri des articles.
     * @param string $action : L'action que l'on veut faire (correspond aux actions dans le routeur).
     * @param array $params : Les autres paramètres de l'URL sous la forme ['param1' => 'valeur1', 'param2' => 'valeur2']
     * @param string|null $tri : La colonne par laquelle trier (peut être null).
     * @param array $triParams : Les paramètres de tri sous la forme ['tri' => 'colonne', 'direction' => 'ASC|DESC']
     * @return string : L'URL générée.
     */
    public static function generateUrl(string $action, array $params, ?string $tri = null, array $triParams = []) : string
    {
        $url = "index.php?action=$action";
        if ($tri !== null) {
            $direction = ($triParams[0] === $tri && $triParams[1] === 'ASC') ? 'DESC' : 'ASC';
            $url .= "&tri=$tri&direction=$direction";
        }
        foreach ($params as $paramName => $paramValue) {
            $url .= "&$paramName=$paramValue";
        }
        return $url;
    }

    /**
     * Cette méthode retourne le code js a insérer en attribut d'un bouton.
     * pour ouvrir une popup "confirm", et n'effectuer l'action que si l'utilisateur
     * a bien cliqué sur "ok".
     * @param string $message : le message à afficher dans la popup.
     * @return string : le code js à insérer dans le bouton.
     */
    public static function askConfirmation(string $message) : string
    {
        return "onclick=\"return confirm('$message');\"";
    }

    /**
     * Cette méthode protège une chaine de caractères contre les attaques XSS.
     * De plus, elle transforme les retours à la ligne en balises <p> pour un affichage plus agréable. 
     * @param string $string : la chaine à protéger.
     * @return string : la chaine protégée.
     */
    public static function format(string $string) : string
    {
        // Etape 1, on protège le texte avec htmlspecialchars.
        $finalString = htmlspecialchars($string, ENT_QUOTES);

        // Etape 2, le texte va être découpé par rapport aux retours à la ligne, 
        $lines = explode("\n", $finalString);

        // On reconstruit en mettant chaque ligne dans un paragraphe (et en sautant les lignes vides).
        $finalString = "";
        foreach ($lines as $line) {
            if (trim($line) != "") {
                $finalString .= "<p>$line</p>";
            }
        }
        
        return $finalString;
    }

    /**
     * Cette méthode permet de récupérer une variable de la superglobale $_REQUEST.
     * Si cette variable n'est pas définie, on retourne la valeur null (par défaut)
     * ou celle qui est passée en paramètre si elle existe.
     * @param string $variableName : le nom de la variable à récupérer.
     * @param mixed $defaultValue : la valeur par défaut si la variable n'est pas définie.
     * @return mixed : la valeur de la variable ou la valeur par défaut.
     */
    public static function request(string $variableName, mixed $defaultValue = null) : mixed
    {
        return $_REQUEST[$variableName] ?? $defaultValue;
    }


    /**
     * initialise le token CSRF dans la session.
     *
     * @return void
    */
    public static function initCsrfToken() : void
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    /**
     * Vérifie si le token CSRF est valide.
     *
     * @return void
    */
    public static function checkCsrfToken() : void
    {
        try {
            if (
                !isset($_SESSION['csrf_token']) || 
                !isset($_REQUEST['csrf_token']) || 
                $_SESSION['csrf_token'] !== $_REQUEST['csrf_token']
                ) {
                throw new Exception("Erreur de vérification du token CSRF.");
            }
            self::generateCsrfToken();
        } catch (Exception $e) {
            $errorView = new View('Erreur');
            $errorView->render('errorPage', ['errorMessage' => $e->getMessage()]);
            exit();
        }
       
    }

    /**
     * Génère un token CSRF.
     *
     * @return string : le token CSRF.
    */
    public static function generateCsrfToken() : string
    {
        return $_SESSION['csrf_token'];
    }

    /**
     * on recupère le token CSRF dans la session.
     * 
     * @return string : le token CSRF.
     */
    public static function getCsrfToken() : string
    {
        return $_SESSION['csrf_token'];
    }


}