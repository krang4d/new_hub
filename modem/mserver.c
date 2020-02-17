#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <errno.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <sys/un.h>

#define SOCK_NAME "/tmp/modem.sock"
#define BUF_SIZE 256

/* Initialize socket structure */
static struct sockaddr_un srvr_name, rcvr_name;
static char buf[BUF_SIZE];
static int   sock, newsock;
static int   bytes, pid;
static socklen_t namelen;

//destrucor and constructor
static void _destruct(void) {
  printf("Colled out __destructor__\n");
  close(sock);
  unlink(SOCK_NAME);
}
static void _construct(void) {
  srvr_name.sun_family = AF_UNIX;
  strcpy(srvr_name.sun_path, SOCK_NAME);
  printf("Colled out __constructor__\n");
}
void __attribute__((constructor)) _construct(); 
void __attribute__((destructor)) _destruct(); 

void doProcessing (int sock);

int main(int argc, char ** argv)
{
  /* First call to socket() function */
  sock = socket(AF_UNIX, SOCK_DGRAM, 0);
  if (sock < 0) 
  {
    perror("socket failed");
    return EXIT_FAILURE;
  }

  /* Now bind the host address using bind() call.*/
  if (bind(sock, &srvr_name, strlen(srvr_name.sun_path) +
        sizeof(srvr_name.sun_family)) < 0) 
  {
    perror("bind failed");
    return EXIT_FAILURE;
  }

   /* Now start listening for the clients, here
      * process will go in sleep mode and will wait
      * for the incoming connection
   */
   listen(sock, 5);
   int addlen = sizeof(rcvr_name);

   while (1) {
      newsock = accept(sock, (struct sockaddr_un *) &rcvr_name, &addlen);
    
      if (newsock < 0) {
         perror("ERROR on accept");
         exit(1);
      }
      
      /* Create child process */
      pid = fork();
    
      if (pid < 0) {
         perror("ERROR on fork");
         exit(1);
      }
      
      if (pid == 0) {
         /* This is the client process */
         close(sock);
         doProcessing(newsock);
         exit(0);
      }
      else {
         close(newsock);
      }
    
   } /* end of while */



  bytes = recvfrom(sock, buf, BUF_SIZE-1, 0, &rcvr_name, &namelen);
  if (bytes < 0) 
  {
    perror("recvfrom failed");
    return EXIT_FAILURE;
  }
  //buf[bytes] = 0;
  //rcvr_name.sun_path[namelen] = 0;
  //printf("Client sent: %s\n", buf);
  close(sock);
  unlink(SOCK_NAME);
}
 
void doProcessing (int sock) {
   int n;
   char buffer[BUF_SIZE];
   bzero(buffer,BUF_SIZE);

   n = read(sock, buffer, BUF_SIZE-1);
   
   if (n < 0) {
      perror("ERROR reading from socket");
      exit(1);
   }
   
   printf("Here is the message: %s\n",buffer);
   n = write(sock,"I got your message",18);
   
   if (n < 0) {
      perror("ERROR writing to socket");
      exit(1);
   }
}
