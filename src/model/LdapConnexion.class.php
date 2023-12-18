<?php

class LdapConnexion {
    private $ldap_host; //hote
    private $ldap_port; //port
    private $ldap_domain; //nom de domaine
    private $ldap_conn;

    public function __construct($host, $port, $domain) {
        $this->ldap_host = $host;
        $this->ldap_port = $port;
        $this->ldap_domain = $domain;

        $this->connect(); //on call la fct dans le constructeur ->connexion lors de l'instanciation
    }

    private function connect() { //methode pour se connecter
        $this->ldap_conn = ldap_connect($this->ldap_host, $this->ldap_port);
        if (!$this->ldap_conn) {
            throw new Exception("Impossible de se connecter au serveur LDAP.");
        }
        ldap_set_option($this->ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldap_conn, LDAP_OPT_REFERRALS, 0);
    }

    public function __destruct() { //destructeur pour fermer la connexion
        $this->disconnect();
    }

    public function disconnect() { //methode pour fermer la co
        if ($this->ldap_conn) {
            ldap_close($this->ldap_conn);
        }
    }

    public function authentification($name, $mdp) { //methode pour s'authentifier
        if (!empty($name) && !empty($mdp) && $this->isValidInput($name, $mdp)) { //si les valeurs ne sont pas vide et on appelle la methode isvalidinput
            $ldap_dn = "uid=" . $name . ",dc=SERVEUR_GR5,dc=lan";
            if (@ldap_bind($this->ldap_conn, $ldap_dn, $mdp)) { //on verif si l'user existe
                return $this->verifierAppartenanceGroupe($name, "cn=Administrateurs,ou=groupes,dc=SERVEUR_GR5,dc=lan", "dc=SERVEUR_GR5,dc=lan"); //si l'user existe on appelle la methode appartenancegroupe pour verif si l'user appaartient a admin oj pas
            }
        }
        return false;
    }

    private function verifierAppartenanceGroupe($username, $group_dn, $base_dn) { //methode pour verifier si un user appartient au groupe admin
        $search_filter = "(&(uid=$username)(memberOf=$group_dn))";
        $result = ldap_search($this->ldap_conn, $base_dn, $search_filter);
        $entries = ldap_get_entries($this->ldap_conn, $result);

        return ($entries["count"] > 0); //renvoie true ou false en fct du nbre de ligne trouvé
    }

    private function isValidInput($name, $mdp) {
        return preg_match('/^[a-zA-Z0-9_]+$/', $name) && strlen($mdp) > 0;
    }
}
