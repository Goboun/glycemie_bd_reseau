<?php
    declare(strict_types=1);

    /**
     * Affiche sous forme de liste les caractéristiques de l'adresse IP du visiteur de la page
     * @author HEBC
     * @return string res la liste les attributs de l'ip
     */
    function ipinfo():string{
        $url = "https://ipinfo.io/".$_SERVER["REMOTE_ADDR"]."/geo";
        $jsonEncode = file_get_contents($url);
        $jsonDecode = json_decode($jsonEncode, true);
        $res = "<ul>\n";
            $res.= "\t\t\t\t\t\t<li>Code Postal : ".$jsonDecode["postal"]."</li>\n";
            $res.= "\t\t\t\t\t\t<li>Ville : ".$jsonDecode["city"]."</li>\n";
            $res.= "\t\t\t\t\t\t<li>Région : ".$jsonDecode["region"]."</li>\n";
            $res.= "\t\t\t\t\t\t<li>Pays : ".$jsonDecode["country"]."</li>\n";
            $res.= "\t\t\t\t\t\t<li>Localisation : ".$jsonDecode["loc"]."</li>\n";
        $res.= "\t\t\t\t\t</ul>\n";
        return $res;
    }

    function get_navigateur():string{
        $res = $_ENV['HTTP_USER_AGENT'];

        if (preg_match('/(Firefox|Chrome|Safari|Opera|MSIE)/', $res, $matches)) {
            return $matches[1];
        } else {
            return "Navigateur inconnu";
        }
    }

    function hashPassword(string $password): string {
        return hash('sha256', $password);
    }
    
    function verifyPassword(string $inputPassword, string $storedPassword): bool {
        return hash('sha256', $inputPassword) === $storedPassword;
    }
?>