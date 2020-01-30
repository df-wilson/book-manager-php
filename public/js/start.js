const home = {
   template: `
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-10 col-sm-9 col-md-7 col-lg-6">
               <h1>Book Manager</h1>
            </div>
         </div>
      </div>
   `,
   
   name: 'home',
   data () {
      return {
         title: 'home',
         loggedIn: false
      }
   },
   mounted()
   {
      this.loggedIn = document.getElementById("isLoggedIn").value;
      if(this.loggedIn == true || this.loggedIn == "true") {
         router.push('book-manager');
      } else {
         window.location.replace('/login');
      }
   }
};
