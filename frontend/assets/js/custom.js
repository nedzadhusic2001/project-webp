
$("main#spapp > section").height($(document).height() - 60);

var app = $.spapp({pageNotFound : 'not_found',
   defaultView: "#login",
  templateDir: "./views/"
}); // initialize

// define routes
app.route({
  view: 'sign_up',
});

app.route({
    view: 'log_in',
  });

  app.route({
    view: 'products',
  });

  app.route({
    view: 'single-product',
  });

  app.route({
    view: 'about_us',
  });

  app.route({
    view: 'contact',
  });

app.route({
  view: 'home',
});

app.route({
  view: 'about',
});

app.route({
  view: 'services',
});

app.route({
  view: 'packages',
});

app.route({
  view: 'blog',
});



// run app
app.run();

