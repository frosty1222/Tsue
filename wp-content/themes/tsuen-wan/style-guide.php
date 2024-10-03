<?php  /**
 * Template Name: Style Guide
 *
 * @package WordPress
 * @subpackage DSG
 * @since DSG 1.0
 */ 
get_header(); ?>

<main id="main" class="main-content">


<div class="container">
    <h1>Heading 1 </h1>
    <h2 >Heading 2</h2>
    <h3 >Heading 3 </h3>
    <h4 >Heading 4 </h4>
    <h5 >Heading 5 </h5>
    <h6 >Heading 6 </h6>

    <p >Lorem ipsum dolor sit amet consectetur adipisicing elit. Aspernatur nesciunt molestiae, enim earum quidem facere veritatis minus nulla quos reprehenderit quaerat dolor sequi ipsam repellat et facilis autem. Eligendi, laborum. </p>
    
    <div class="link margin-bottom" > <a href="#">Text Link</a> </div>
    <div class="button margin-bottom" >
        <a href="#" class="button-read-more"><span>Explore More</span></a>
        
    </div>
    <div class="arrow-button big ">
        <a class="arrow-prev-button arrow-button-all margin-bottom   ">
            PREV
            <div class="svg"><svg width="187" height="14" viewBox="0 0 187 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M186.5 13L2 13L20.5 1V8.5" stroke="black"/>
            </svg></div>
        
        </a>

        <a class="arrow-next-button arrow-button-all margin-bottom   ">
            NEXT
            <div class="svg"><svg width="187" height="14" viewBox="0 0 187 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 13L184.5 13L166 1V8.5" stroke="black"/>
                </svg>
            </div>
        
        </a>
    </div>

    <div class="arrow-button disable big ">
    <a class="arrow-prev-button arrow-button-all-disable   ">
            PREV
            <div class="svg"><svg width="187" height="14" viewBox="0 0 187 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M186.5 13L2 13L20.5 1V8.5" />
            </svg></div>
        
        </a>

        

    </div>

    <div class="arrow-button small ">
        <a class="arrow-prev-button arrow-button-all margin-bottom  ">
            PREV
            <div class="svg"><svg width="82" height="14" viewBox="0 0 82 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M82 13.0006L2 13L19.2615 1V8.49999" stroke="black"/>
        </svg></div>
        
        </a>

        <a class="arrow-next-button arrow-button-all margin-bottom  ">
            NEXT
            <div class="svg"><svg width="82" height="14" viewBox="0 0 82 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 13.0006L80 13L62.7385 1V8.5" stroke="black"/>
                </svg>

            </div>
        
        </a>
    </div>

    <div class="arrow-button disable small ">
    <a class="arrow-prev-button arrow-button-all-disable  ">
            PREV
            <div class="svg"><svg width="82" height="14" viewBox="0 0 82 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M82 13.0006L2 13L19.2615 1V8.49999" />
</svg></div>
        
        </a>

    
    
</main>

<?php get_footer(); ?>
