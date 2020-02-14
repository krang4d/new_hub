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
  int   sock,count;
  sock = socket(AF_UNIX, SOCK_DGRAM, 0);
  char buf[BUF_SIZE];
  struct sockaddr_un srvr_name;

  if (sock < 0) 
  {
    perror("socket failed");
    return EXIT_FAILURE;
  }
  srvr_name.sun_family = AF_UNIX;
  strcpy(srvr_name.sun_path, SOCK_NAME);
  connect(sock, srvr_name.sun_path, strlen(srvr_name.sun_path));
  strcpy(buf, "Hello, Unix sockets!");
  count=sendto(sock, buf, strlen(buf), 0, &srvr_name,
    strlen(srvr_name.sun_path) + sizeof(srvr_name.sun_family));
  printf("Передана строка: %s - что составляет %i байт\n",buf,count);
}
