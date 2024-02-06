import React, { useState, useEffect } from "react";

function SinglePost({ match }) {
    const [post, setPost] = useState({});

    useEffect(() => {
        fetchPost();
    }, []);

    const fetchPost = async () => {
        const fetchItem = await fetch(`/wp-json/wp/v2/posts`);
        const post = await fetchItem.json();
        setPost(post);
    };

    return (
        <div className="single-post">
            <h1>{post.title}</h1>
            <p>{post.body}</p>
        </div>
    );
}

export default SinglePost;