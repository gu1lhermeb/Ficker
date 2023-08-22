"use client";
import MainContext from "@/context";
import { useContext, useEffect, useState } from "react";
import EnterTransaction from "./EnterTransaction/page";
import { HomeScreen } from "./pages/Home/Home";
import { Cookies } from "react-cookie";
import { Spin } from "antd";

export default function Home() {
  const { auth, setAuth } = useContext(MainContext);
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    const token = localStorage.getItem("token");
    if (token) {
      setAuth(true);
    }
    setLoading(false);
  }, []);

  if (loading)
    return (
      <div
        style={{
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          height: "100vh",
        }}
      >
        <Spin size="large" />
      </div>
    );

  if (auth) return <EnterTransaction />;

  return <EnterTransaction />;
}
