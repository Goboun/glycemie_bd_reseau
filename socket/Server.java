import java.io.*;
import java.net.*;
//Rappel : * prends toutes les méthodes et classes de io et net

public class Server {
    public static void main(String[] args) {

        try () {
            int portS = 8080;
            //création du socket sur le port 8080
            ServerSocket serverSocket = new ServerSocket(8080);

            System.out.println("Serveur en attente de connexion...");
            Socket socket = serverSocket.accept();
            System.out.println("Client connecté.");

            BufferedReader in = new BufferedReader(new InputStreamReader(socket.getInputStream()));
            PrintWriter out = new PrintWriter(socket.getOutputStream(), true);

            //login du client
            String clientId = in.readLine();
            System.out.println("Idclient : " + clientId);
            out.println("Client connecté : " + clientId);

            //traitement des requêtes du client
            //POUR l'INSTANT ON PEUT QUE UPDATE OU GET ou évidemment quitter
            String request;
            while ((request = in.readLine()) != null) {
                //cas de mise à jour de la base de données
                if (request.startsWith("UPDATE")) {
                    //confirmation de la mise à jour la base de données
                    System.out.println("Mise à jour réussie");
                }
                //cas récupération de données (requête GET?)
                else if (request.startsWith("GET")) {
                    //confirmation de la mise à jour la base de données
                    System.out.println("Données demandées");
                }
                //fermeture du socket côté serv
                else if (request.equals("QUIT")) {
                    System.out.println("Fermeture de la connexion");
                    break;
                }
            }
            System.out.println("Fermeture de la connexion en cours...");
            socket.close();
            System.out.println("Connexion fermée");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}