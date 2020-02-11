all: arctichubd  
arctichubd:
	gcc arctichubd.c -D_REENTERANT -o arctichubd
	strip arctichubd
install:
	cp arctichubd ~/bin
clean:
	 rm -f *.o   
	 rm -f arctichubd
	 rm -f *~
