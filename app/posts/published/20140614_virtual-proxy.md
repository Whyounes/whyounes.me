title: The Virtual Proxy Pattern
author: Adam Wathan
slug: the-virtual-proxy-pattern
date: 2014-06-14

Another post of mine from the Vehikl blog last month, this time about one of the interesting patterns I used when developing [Faktory](https://github.com/vehikl/faktory).

> When I was working on the relationship component of Faktory, I ran into a problem where telling a relationship to use a factory that wasn’t defined until later would blow everything up.
>
> It would be annoying to have to define all of your factories in a carefully controlled order, so I needed to figure out a way to defer the loading of a factory until the relationship was actually being generated.
>
> I didn’t want a relationship to know or care that I was lazy-loading the factory it needed, so I created a virtual proxy that could stand in without the relationship realizing it wasn’t carrying the actual factory instance...

Check out the rest here:

[http://transmission.vehikl.com/the-virtual-proxy-pattern/](http://transmission.vehikl.com/the-virtual-proxy-pattern/)
