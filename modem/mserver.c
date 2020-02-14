#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <errno.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <sys/un.h>

#define SOCK_NAME "/tmp/modem.sock"
#define BUF_SIZE 256

int main(int argc, char ** argv)
{
  struct sockaddr_un srvr_name, rcvr_name;
  char buf[BUF_SIZE];
  int   sock;
  int   bytes;
  socklen_t namelen;

  sock = socket(AF_UNIX, SOCK_DGRAM, 0);
  if (sock < 0) 
  {
    perror("socket failed");
    return EXIT_FAILURE;
  }
  srvr_name.sun_family = AF_UNIX;
  strcpy(srvr_name.sun_path, SOCK_NAME);
  if (bind(sock, &srvr_name, strlen(srvr_name.sun_path) +
        sizeof(srvr_name.sun_family)) < 0) 
  {
    perror("bind failed");
    return EXIT_FAILURE;
  }
  bytes = recvfrom(sock, buf, BUF_SIZE-1, 0, &rcvr_name, &namelen);
  if (bytes < 0) 
  {
    perror("recvfrom failed");
    return EXIT_FAILURE;
  }
  buf[bytes] = 0;
  rcvr_name.sun_path[namelen] = 0;
  printf("Client sent: %s\n", buf);
  close(sock);
  unlink(SOCK_NAME);
}
 
