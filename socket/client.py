import socket

def main():
    server_address = ('localhost', 1010) #localhost ou par l'ip 127.0.0.1 comme on connait

    #Création d'un socket TCP !!!! (TCP)
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as sock:
        #Connexion au serveur
        sock.connect(server_address)

        #Envoi de l'identifiant du client, "ClientDefaultID" par défaut ici
        client_id = "ClientDefaultID"
        sock.sendall(client_id.encode('utf-8'))
        #Message de confirmation de login
        print(sock.recv(1024).decode('utf-8'))  

        #Envoi d'une mise à jour
        sock.sendall(b'UPDATE SET value="example"') #On remplacera "exemple" quand nécessaire
        #Message de confirmation de la mise à jour
        print(sock.recv(1024).decode('utf-8'))

        #Requête pour obtenir les datas de la BD
        sock.sendall(b'GET DATA')
        #Réponse de notre serv
        print(sock.recv(1024).decode('utf-8'))

        #Déconnexion du socket
        sock.sendall(b'QUIT')
        #Confirmation de fermeture
        print(sock.recv(1024).decode('utf-8'))

if __name__ == "__main__":
    main()