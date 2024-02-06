import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import Shimmer from "./Shimmer";

function HomePage() {
    const [posts, setPosts] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [itemsPerPage, setItemsPerPage] = useState(3);
    const [loading, setLoading] = useState(false);
    useEffect(() => {
        const fetchPosts = async () => {
          setLoading(true);
          try {
            const response = await fetch('/wp-json/wp/v2/posts?per_page=5');
            const data = await response.json();
    
            // Fetch additional data for featured images
            const postsWithImages = await Promise.all(
              data.map(async (post) => {
                const imageResponse = await fetch(`/wp-json/wp/v2/media/${post.featured_media}`);
                const imageData = await imageResponse.json();

                return {
                  ...post,
                  featuredImage: imageData,
                };
              })
            );
    
            setPosts(postsWithImages);
            setLoading(false);
          } catch (error) {
            console.error('Error fetching movies:', error);
            setLoading(false);
          }
        };
        fetchPosts();
    }, []);
    const totalPages = Math.ceil(posts.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentItems = posts.slice(startIndex, endIndex);
    const nextPage = () => {
        setCurrentPage(prevPage => prevPage + 1);
    };
    const prevPage = () => {
        setCurrentPage(prevPage => prevPage - 1);
    };
    const formatPostDate = (date) => {
        const dateObj = new Date(date);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Intl.DateTimeFormat('en-US', options).format(dateObj);
    };
    return (
        <>
        {loading && <Shimmer count={itemsPerPage}/>}
        <div className="home-page">
        {currentItems && currentItems.map((post, index) => (
            <div key={index} className="single-post">
            <Link 
            key={index}
            to={{ 
                pathname: `/${post.slug}`,
                state: { post: post } 
            }}
            className="post-link">
                <img
                    src={post.featuredImage.media_details.sizes.movie_card.source_url}
                    alt={post.title.rendered}
                />
                <div class="single-text">
                    <h2 dangerouslySetInnerHTML={{ __html: post.title.rendered }} />
                    <p className="post-date">{formatPostDate(post.date)}</p>
                    <p dangerouslySetInnerHTML={{ __html: post.content.rendered }} />
                </div>
            </Link>
            </div>
        ))}
        </div>
        </>
    );
}
export default HomePage;