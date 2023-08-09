import Image from "next/image";
import styles from "./page.module.css";
import Login from "./login/page";
import { HomeScreen } from "./pages/Home/Home";
import EnterTransaction from "./EnterTransaction/page";

export default function Home() {
  return <EnterTransaction />;
}
