import axios from "axios"
import { useEffect, useState } from "react"

export default function ConfirmPost() {

    const [product, setProducts] = useState([]);

    useEffect(() => {
        fetchProducts()
    }, [])

    const fetchProducts = async () => {
        await axios.get('http://localhost:8000/api/blogs/').then(({ data }) => {
            setProducts(data)
        })
    }
    return (
        'Helllo world'
    )
}